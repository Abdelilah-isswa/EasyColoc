{{-- resources/views/colocations/my.blade.php --}}
@extends('layouts.app')

@section('title', 'My Colocations')

@section('content')
<h1 class="text-2xl font-bold mb-4">My Colocations</h1>

@if($colocations->isEmpty())
    <p>You have no colocations yet.</p>
@else
    <div class="space-y-4">
    @foreach($colocations as $colocation)
        @php
            $membership = $colocation->members->contains(auth()->id()) ? $colocation->members->find(auth()->id())->pivot : null;
        @endphp

        {{-- Skip colocations user has left --}}
        @if($membership && $membership->left_at)
            @continue
        @endif

        <div class="bg-white p-4 rounded shadow flex justify-between items-center">
            <div>
                <a href="{{ route('colocations.show', $colocation) }}" class="font-semibold text-blue-600 hover:underline">
                    {{ $colocation->name }}
                </a>
                <div class="text-sm text-gray-600 mt-1">
                    Status: 
                    <span class="{{ $colocation->status === 'cancelled' ? 'text-red-600' : 'text-green-600' }}">
                        {{ ucfirst($colocation->status) }}
                    </span>
                    | Members: {{ $colocation->members->count() }}
                    | Expenses: {{ $colocation->expenses->count() }}
                </div>
            </div>

            <div class="flex space-x-2">
                {{-- Leave button for non-owners --}}
                @if($membership && $membership->role !== 'owner' && $colocation->status !== 'cancelled')
                    <form action="{{ route('colocations.leave', $colocation) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Leave Colocation
                        </button>
                    </form>
                @endif

                {{-- Cancel button for owner --}}
                @if($colocation->owner_id === auth()->id() && $colocation->status !== 'cancelled')
                    <form action="{{ route('colocations.cancel', $colocation) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                            Annulation d’une colocation
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
    </div>
@endif
@endsection