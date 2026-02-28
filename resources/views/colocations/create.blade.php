@extends('layouts.app')

@section('title', 'Create Colocation')

@section('content')
@auth
    @if(auth()->user()->global_role !== 'admin' && auth()->user()->activeMembership())
        <p class="text-red-500">
            You already have an active colocation. You cannot create another.
        </p>
    @else
        <div class="max-w-lg mx-auto bg-white p-6 rounded shadow mt-6">
            <h1 class="text-2xl font-bold mb-4">Create a New Colocation</h1>

            <form method="POST" action="{{ route('colocations.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-2 font-semibold" for="name">Colocation Name</label>
                    <input type="text" name="name" id="name" required 
                        class="border p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Create Colocation
                </button>
            </form>
        </div>
    @endif
@endauth
@endsection