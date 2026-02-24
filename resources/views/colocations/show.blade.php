{{-- resources/views/colocations/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Colocation: ' . $colocation->name)

@section('content')
    <h1 class="text-2xl font-bold mb-4">Colocation: {{ $colocation->name }}</h1>

    {{-- Owner --}}
    <div class="mb-4">
        <strong>Owner:</strong> {{ $colocation->owner->name }}
    </div>

    {{-- Members --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Members</h2>
        @if($colocation->members->isEmpty())
            <p>No members yet.</p>
        @else
            <ul class="list-disc ml-6">
                @foreach($colocation->members as $member)
                    <li>
                        {{ $member->name }} 
                        (Role: {{ $member->pivot->role ?? 'Member' }})
                        @if($member->pivot->left_at)
                            - Left at {{ $member->pivot->left_at }}
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Expenses --}}
    <div>
        <h2 class="text-xl font-semibold mb-2">Expenses</h2>
        @if($colocation->expenses->isEmpty())
            <p>No expenses yet.</p>
        @else
            <table class="w-full border border-gray-300 bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Amount</th>
                        <th class="px-4 py-2 border">Payeur</th>
                        <th class="px-4 py-2 border">Category</th>
                        <th class="px-4 py-2 border">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($colocation->expenses as $expense)
                        <tr>
                            <td class="px-4 py-2 border">{{ $expense->title }}</td>
                            <td class="px-4 py-2 border">{{ $expense->amount }}</td>
                            <td class="px-4 py-2 border">{{ $expense->payeur->name }}</td>
                            <td class="px-4 py-2 border">{{ $expense->category->name }}</td>
                            <td class="px-4 py-2 border">{{ $expense->date->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection