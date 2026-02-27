{{-- resources/views/colocations/my.blade.php --}}
@extends('layouts.app')

@section('title', 'My Colocations')

@section('content')
<h1 class="text-2xl font-bold mb-4">My Colocations</h1>

@if($colocations->isEmpty())
    <p>You have no colocations yet.</p>
@else
@foreach($colocations as $colocation)
    <div class="colocation-card">
         <a href="{{ route('colocations.show', $colocation) }}" class="font-semibold text-blue-600 hover:underline">
                    {{ $colocation->name }}
                </a>
        
        
            
       

        @if($colocation->pivot->role !== 'owner')
            <form action="{{ route('colocations.leave', $colocation) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Leave Colocation
                </button>
            </form>
        @endif
        <div class="text-sm text-gray-600">
                    Members: {{ $colocation->members->count() }}
                    | Expenses: {{ $colocation->expenses->count() }}
                </div>
    </div>
@endforeach
@endif
@endsection



