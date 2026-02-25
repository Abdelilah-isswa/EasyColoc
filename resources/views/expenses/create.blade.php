@extends('layouts.app')

@section('title', 'Add Expense to ' . $colocation->name)

@section('content')
<div class="max-w-lg mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Add Expense to {{ $colocation->name }}</h1>

    <form method="POST" action="{{ route('expenses.store', $colocation) }}">
        @csrf

        <label class="block mb-2">Title</label>
        <input type="text" name="title" required class="border p-2 w-full mb-4">

        <label class="block mb-2">Amount</label>
        <input type="number" step="0.01" name="amount" required class="border p-2 w-full mb-4">

        <label class="block mb-2">Category</label>
        <select name="category_id" required class="border p-2 w-full mb-4">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

      
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Add Expense</button>
    </form>
</div>
@endsection