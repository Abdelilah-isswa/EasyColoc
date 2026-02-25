{{-- resources/views/colocations/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Colocation: ' . $colocation->name)

@section('content')
    <h1 class="text-2xl font-bold mb-4">Colocation: {{ $colocation->name }}</h1>



    <div class="mb-6">
    

    {{-- Add category form for Owner --}}
    @can('manage', $colocation)
        <form action="{{ route('categories.store', $colocation) }}" method="POST" class="mb-4 flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="New category" class="border p-2 flex-1" required>
            <button class="bg-green-500 text-white px-4 py-2">Add</button>
        </form>
    @endcan

  {{-- Categories Section --}}
<div class="mb-6">
    <h2 class="text-xl font-semibold mb-2">Categories</h2>

    {{-- Add category form for Owner --}}
    @if(auth()->id() === $colocation->owner_id)
        <form action="{{ route('categories.store', $colocation) }}" method="POST" class="mb-4 flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="New category" class="border p-2 flex-1" required>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Add Category
            </button>
        </form>
    @endif

    {{-- List categories --}}
    @if($colocation->categories->isEmpty())
        <p>No categories yet.</p>
    @else
        <ul class="list-disc ml-6">
            @foreach($colocation->categories as $category)
                <li class="flex justify-between items-center mb-1">
                    {{ $category->name }}
                    @if(auth()->id() === $colocation->owner_id)
                        <form action="{{ route('categories.destroy', [$colocation, $category]) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
</div>
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
                            <td class="px-4 py-2 border">{{ $expense->date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@php
    $userId = auth()->id();
    $canParticipate = $colocation->owner_id === $userId || $colocation->members->contains($userId);
@endphp

@if($canParticipate)
    <div class="mb-4">
        <a href="{{ route('expenses.create', $colocation) }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add Expense
        </a>
    </div>
@endif
@endsection