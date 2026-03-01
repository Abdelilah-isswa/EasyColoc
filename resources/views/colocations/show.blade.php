{{-- resources/views/colocations/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Colocation: ' . $colocation->name)

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $colocation->name }}</h1>
            <p class="text-gray-600 mt-1">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Owner: {{ $colocation->owner->name }}
                </span>
            </p>
        </div>

        @php
        $userId = auth()->id();
        $canParticipate = $colocation->owner_id === $userId || $colocation->members->contains($userId);
        @endphp

        @if($canParticipate)
        <div class="mt-4 md:mt-0">
            <a href="{{ route('expenses.create', $colocation) }}"
                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Expense
            </a>
        </div>
        @endif
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="bg-blue-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Members</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $colocation->members->count()}}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="bg-green-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Expenses</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $colocation->expenses->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="bg-purple-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-5-5A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Categories</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $colocation->categories->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Members & Categories --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Members Card --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Members
                    </h2>
                </div>
                <div class="p-4">
                    @if($colocation->members->isEmpty())
                    <p class="text-gray-500 text-center py-4">No members yet</p>
                    @else
                    <ul class="space-y-3">
                        @foreach($colocation->members as $member)
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $member->pivot->role ?? 'member' }}</p>
                                </div>
                            </div>
                            @if(auth()->id() === $colocation->owner_id && auth()->id() !== $member->id)
                            <form action="{{ route('colocations.members.remove', [$colocation, $member]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this member?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    {{-- Invite Form for Owner --}}
                    @if(auth()->id() === $colocation->owner_id)
                    <form action="{{ route('invitations.send', $colocation) }}" method="POST" class="mt-4 pt-4 border-t border-gray-200">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 mb-2">Invite new member</label>
                        <div class="flex gap-2">
                            <input type="email" name="email" placeholder="Email address" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors duration-200">
                                Send
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Categories Card --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-5-5A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Categories
                    </h2>
                </div>
                <div class="p-4">
                    @if($colocation->categories->isEmpty())
                    <p class="text-gray-500 text-center py-4">No categories yet</p>
                    @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($colocation->categories as $category)
                        <div class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1">
                            <span class="text-sm text-gray-700">{{ $category->name }}</span>
                            @if(auth()->id() === $colocation->owner_id)
                            <form action="{{ route('categories.destroy', [$colocation, $category]) }}" method="POST" class="ml-2" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Add Category Form for Owner --}}
                    @if(auth()->id() === $colocation->owner_id)
                    <form action="{{ route('categories.store', $colocation) }}" method="POST" class="mt-4 pt-4 border-t border-gray-200">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="name" placeholder="New category" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                            <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600 transition-colors duration-200">
                                Add
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: Balances, Expenses & Settlements --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Balances Card --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Current Balances
                    </h2>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($colocation->members as $member)
                        @php
                        $balance = $balances[$member->id] ?? 0;
                        @endphp
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-semibold text-sm mx-auto mb-2">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                            <p class="text-lg font-bold {{ $balance > 0 ? 'text-green-600' : ($balance < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                {{ $balance > 0 ? '+' : '' }}{{ number_format($balance, 2) }} €
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <form method="GET" class="mb-4 flex items-center space-x-2">
                <label for="month" class="font-medium">Filter by month:</label>
                <select name="month" id="month" class="border rounded px-2 py-1">
                    @foreach($months as $m)
                    <option value="{{ $m }}" {{ $month === $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::parse($m . '-01')->format('F Y') }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Filter</button>
            </form>
            


            {{-- Expenses Card --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                        </svg>
                        Expenses
                    </h2>
                </div>
                <div class="p-4">
                    @if($colocation->expenses->isEmpty())
                    <p class="text-gray-500 text-center py-8">No expenses yet</p>
                    @else
                    <div class="space-y-3">
                        @foreach($expenses as $expense)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg flex items-center justify-center text-white font-semibold">
                                    €
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $expense->title }}</h3>
                                    <p class="text-xs text-gray-500">
                                        {{ $expense->date }} • {{ $expense->payeur->name }} • {{ $expense->category->name }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">{{ number_format($expense->amount, 2) }} €</p>
                                @php
                                $userId = auth()->id();
                                $userSettlement = $expense->settlements
                                ->where('from_user_id', $userId)
                                ->first();
                                @endphp

                                @if($expense->payeur_id === $userId)
                                <span class="text-xs text-green-600 font-medium">You paid</span>
                                @elseif($userSettlement && $userSettlement->paid_at)
                                <span class="text-xs text-green-600 font-medium">Paid</span>
                                @else
                                <form action="{{ route('expenses.pay', [$colocation, $expense]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                        Pay
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Settlements Card --}}
            @if(!$colocation->settlements->isEmpty())
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-4 py-3">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Settlements
                    </h2>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        @foreach($colocation->settlements as $settlement)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    💰
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $settlement->fromUser->name }}</span>
                                        paid
                                        <span class="font-medium">{{ $settlement->toUser->name }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="font-bold text-gray-900">{{ number_format($settlement->amount, 2) }} €</span>
                                @if($settlement->paid_at)
                                <span class="text-xs text-green-600 font-medium">Paid</span>
                                @elseif(auth()->id() === $settlement->from_user_id)
                                <form action="{{ route('settlements.markAsPaid', $settlement) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition-colors duration-200">
                                        Mark Paid
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-red-600 font-medium">Pending</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection