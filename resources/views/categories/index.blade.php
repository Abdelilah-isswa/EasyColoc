@extends('layouts.app')

@section('title', 'Categories for ' . $colocation->name)

@section('content')
    <h1 class="text-2xl font-bold mb-4">Categories for {{ $colocation->name }}</h1>

    {{-- Create new category --}}
    <form method="POST" action="{{ route('categories.store', $colocation) }}" class="mb-4">
        @csrf
        <input type="text" name="name" placeholder="New Category" class="border p-2" required>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2">Add</button>
    </form>

    {{-- List categories --}}
    <ul>
        @foreach($categories as $category)
            <li class="mb-2 flex justify-between items-center">
                {{ $category->name }}
                <form method="POST" action="{{ route('categories.destroy', [$colocation, $category]) }}">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 text-white px-2 py-1 text-sm">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection