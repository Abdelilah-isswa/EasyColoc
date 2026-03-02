<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Support\Facades\DB;

class ColocationController extends Controller
{
    public function show(Colocation $colocation, Request $request)
    {
        $this->authorizeAccess($colocation);
        
        $month = $request->query('month', 'all');
        $allExpenses = $colocation->expenses()->with(['payeur','category','settlements'])->get();
        $expenses = $this->getFilteredExpenses($colocation, $month);
        $members = $this->getActiveMembers($colocation);
        $balances = $this->calculateBalances($colocation, $allExpenses, $members);
        $categoryStats = $this->getCategoryStats($colocation, $expenses);
        $monthlyStats = $this->getMonthlyStats($colocation);
        $months = collect(range(0, 11))->map(fn($i) => now()->subMonths($i)->format('Y-m'));
        $totalPaid = $colocation->settlements()->whereNotNull('paid_at')->sum('amount');

        return view('colocations.show', compact(
            'colocation', 'expenses', 'months', 'month', 'balances', 'members', 'categoryStats', 'monthlyStats','totalPaid'
        ));
    }

    private function authorizeAccess(Colocation $colocation)
    {
        $user = auth()->user();
        $membership = $user->colocations()->where('colocation_id', $colocation->id)->first();

        if (!$membership || $membership->pivot->left_at) {
            abort(403, 'You do not have access to this colocation.');
        }

        if ($colocation->status === 'cancelled') {
            abort(403, "This colocation has been cancelled.");
        }
    }

    private function getFilteredExpenses(Colocation $colocation, $month)
    {
        $query = $colocation->expenses()->with(['payeur','category','settlements']);
        
        if ($month !== 'all') {
            $query->whereYear('date', substr($month, 0, 4))->whereMonth('date', substr($month, 5, 2));
        }
        
        return $query->get();
    }

    private function getActiveMembers(Colocation $colocation)
    {
        return $colocation->memberships()->with('user')->whereNull('left_at')->get()->map(fn($m) => $m->user);
    }

    private function calculateBalances(Colocation $colocation, $allExpenses, $members)
    {
        $balances = [];

        foreach ($members as $member) {
            $balances[$member->id] = 0;
        }

        foreach ($allExpenses as $expense) {
            $activeMemberIds = $this->getActiveMembersForExpense($colocation, $expense);
            
            if (count($activeMemberIds) === 0) continue;

            $share = $expense->amount / count($activeMemberIds);

            foreach ($activeMemberIds as $userId) {
                if (!isset($balances[$userId])) $balances[$userId] = 0;
                
                if ($userId == $expense->payeur_id) {
                    $balances[$userId] += ($expense->amount - $share);
                } else {
                    $balances[$userId] -= $share;
                }
            }
        }

        $this->applySettlements($colocation, $balances);

        return $balances;
    }

    private function getActiveMembersForExpense(Colocation $colocation, $expense)
    {
        return $colocation->memberships()
            ->where('joined_at', '<=', $expense->created_at)
            ->where(function ($q) use ($expense) {
                $q->whereNull('left_at')->orWhere('left_at', '>', $expense->created_at);
            })
            ->pluck('user_id')
            ->toArray();
    }

    private function applySettlements(Colocation $colocation, &$balances)
    {
        $settlements = $colocation->settlements()->whereNotNull('paid_at')->get();
        
        foreach ($settlements as $settlement) {
            if (isset($balances[$settlement->from_user_id])) {
                $balances[$settlement->from_user_id] += $settlement->amount;
            }
            if (isset($balances[$settlement->to_user_id])) {
                $balances[$settlement->to_user_id] -= $settlement->amount;
            }
        }
    }

    private function getCategoryStats(Colocation $colocation, $expenses)
    {
        return $colocation->categories->map(function($category) use ($expenses) {
            return [
                'name' => $category->name,
                'total' => $expenses->where('category_id', $category->id)->sum('amount'),
            ];
        });
    }

    private function getMonthlyStats(Colocation $colocation)
    {
        return DB::table('expenses')
            ->selectRaw("to_char(date, 'YYYY-MM') as month, SUM(amount) as total")
            ->where('colocation_id', $colocation->id)
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }

    public function create()
    {
        return view('colocations.create');
    }

    public function myColocations()
    {
        $user = auth()->user();
        $colocations = $user->colocations()->wherePivot('left_at', null)->get();
        return view('colocations.my', compact('colocations'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $user = auth()->user();

        if ($user->global_role !== 'admin' && $user->activeMembership()) {
           return back()->withErrors('You already have an active colocation');
        }

        $colocation = Colocation::create([
            'name' => $request->name,
            'owner_id' => $user->id,
            'status' => 'active'
        ]);

        Membership::create([
            'user_id' => $user->id,
            'colocation_id' => $colocation->id,
            'role' => 'owner',
            'joined_at' => now()
        ]);

        return redirect()->route('colocations.show', $colocation);
    }

    public function removeMember(Colocation $colocation, $user)
    {
        $this->authorizeOwner($colocation);

        if ($user === auth()->id()) {
            return redirect()->back()->withErrors('You cannot remove yourself.');
        }

        $colocation->members()->detach($user);
        return redirect()->back()->with('success', 'Member has been removed from the colocation.');
    }

    public function leave(Colocation $colocation)
    {
        $user = auth()->user();
        $this->validateMembership($colocation, $user);

        $balance = $this->calculateUserBalance($colocation, $user);
        $this->handleLeaveBalance($colocation, $user, $balance);
        $this->softDeleteMembership($colocation, $user);

        return redirect()->route('colocations.my')->with('success', 'You have left the colocation.');
    }

    private function calculateUserBalance(Colocation $colocation, $user)
    {
        $allExpenses = $colocation->expenses;
        $balance = 0;

        foreach ($allExpenses as $expense) {
            $activeMemberIds = $this->getActiveMembersForExpense($colocation, $expense);

            if (count($activeMemberIds) === 0 || !in_array($user->id, $activeMemberIds)) continue;

            $share = $expense->amount / count($activeMemberIds);

            if ($user->id == $expense->payeur_id) {
                $balance += ($expense->amount - $share);
            } else {
                $balance -= $share;
            }
        }

        $settlements = $colocation->settlements()->whereNotNull('paid_at')->get();
        foreach ($settlements as $settlement) {
            if ($settlement->from_user_id == $user->id) {
                $balance += $settlement->amount;
            } elseif ($settlement->to_user_id == $user->id) {
                $balance -= $settlement->amount;
            }
        }

        return $balance;
    }

    private function authorizeOwner(Colocation $colocation)
    {
        if ($colocation->owner_id !== auth()->id()) {
            abort(403, 'Only the owner can perform this action.');
        }
    }

    private function validateMembership(Colocation $colocation, $user)
    {
        $membership = $user->colocations()->where('colocation_id', $colocation->id)->first();

        if (!$membership) {
            abort(403, 'You are not a member of this colocation.');
        }

        if ($membership->pivot->role === 'owner') {
            abort(403, 'Owner cannot leave the colocation.');
        }
    }

    private function handleLeaveBalance(Colocation $colocation, $user, $balance)
    {
        if ($balance < -0.01) {
            $this->redistributeDebt($colocation, $user, abs($balance));
            $user->decrement('reputation_score');
        } else {
            $user->increment('reputation_score');
        }
    }

    private function softDeleteMembership(Colocation $colocation, $user)
    {
        $user->colocations()->updateExistingPivot($colocation->id, ['left_at' => now()]);
    }

    private function processMembers(Colocation $colocation, $allMembers, $balances)
    {
        foreach ($allMembers as $membership) {
            $member = $membership->user;
            if ($member->id === auth()->id()) continue;

            $balance = $balances[$member->id] ?? 0;
            $this->updateReputation($member, $balance);
            $this->softDeleteMembership($colocation, $member);
        }
    }

    private function updateReputation($user, $balance)
    {
        if ($balance < -0.01) {
            $user->decrement('reputation_score');
        } else {
            $user->increment('reputation_score');
        }
    }

    private function groupByMonth($expenses)
    {
        $totals = [];
        foreach ($expenses as $expense) {
            $month = $expense->month;
            $totals[$month] = ($totals[$month] ?? 0) + $expense->amount;
        }
        return $totals;
    }

    private function groupByCategory($expenses)
    {
        $totals = [];
        foreach ($expenses as $expense) {
            $category = $expense->category->name;
            $totals[$category] = ($totals[$category] ?? 0) + $expense->amount;
        }
        return $totals;
    }

    private function redistributeDebt(Colocation $colocation, $user, $debt)
    {
        $remainingMembers = $colocation->memberships()
            ->whereNull('left_at')
            ->where('user_id', '!=', $user->id)
            ->pluck('user_id')
            ->toArray();

        if (count($remainingMembers) === 0) return;

        \App\Models\Expense::create([
            'colocation_id' => $colocation->id,
            'category_id' => $colocation->categories()->first()->id ?? 1,
            'title' => 'Debt redistribution - ' . $user->name . ' left',
            'amount' => $debt,
            'payeur_id' => $user->id,
            'date' => now(),
        ]);

        $sharePerMember = $debt / count($remainingMembers);
        foreach ($remainingMembers as $memberId) {
            \App\Models\Settlement::create([
                'from_user_id' => $memberId,
                'to_user_id' => $user->id,
                'colocation_id' => $colocation->id,
                'amount' => $sharePerMember,
                'paid_at' => null,
            ]);
        }
    }

    public function cancel(Colocation $colocation)
    {
        $this->authorizeOwner($colocation);

        $allMembers = $colocation->memberships()->get();
        $balances = $this->calculateBalances($colocation, $colocation->expenses, $allMembers->map(fn($m) => $m->user));

        $colocation->update(['status' => 'cancelled']);
        $this->processMembers($colocation, $allMembers, $balances);
        $this->softDeleteMembership($colocation, auth()->user());

        return redirect()->route('colocations.my')->with('success', 'Colocation cancelled. All outstanding balances are recorded.');
    }

    public function statistics(Colocation $colocation)
    {
        $expenses = $colocation->expenses;
        $monthlyTotals = $this->groupByMonth($expenses);
        $categoryTotals = $this->groupByCategory($expenses);

        return view('colocations.statistics', compact('colocation', 'monthlyTotals', 'categoryTotals'));
    }

    public function allColocations()
    {
        $colocations = Colocation::with(['owner', 'members'])
            ->withCount('members')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.colocations.index', compact('colocations'));
    }
}
