{{-- resources/views/colocations/my.blade.php --}}
@extends('layouts.app')

@section('title', 'My Colocations')

@section('content')
<h1 class="text-2xl font-bold mb-4">My Colocations</h1>

@if($colocations->isEmpty())
    <p>You have no colocations yet.</p>
@else
    <ul class="space-y-2">
        @foreach($colocations as $coloc)
            <li class="bg-white p-4 border rounded shadow hover:bg-gray-50">
                <a href="{{ route('colocations.show', $coloc) }}" class="font-semibold text-blue-600 hover:underline">
                    {{ $coloc->name }}
                </a>
                <div class="text-sm text-gray-600">
                    Members: {{ $coloc->members->count() }}
                    | Expenses: {{ $coloc->expenses->count() }}
                </div>
            </li>
        @endforeach
    </ul>
@endif
@endsection