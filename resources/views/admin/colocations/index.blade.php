@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">All Colocations</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($colocations as $colocation)
            <a href="{{ route('colocations.show', $colocation) }}" 
               class="block p-4 bg-white rounded shadow hover:bg-blue-50 transition">
                <h2 class="text-lg font-semibold">{{ $colocation->name }}</h2>
                <p class="text-sm text-gray-500">Owner: {{ $colocation->owner->name }}</p>
                <p class="text-sm text-gray-500">Status: {{ ucfirst($colocation->status) }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection