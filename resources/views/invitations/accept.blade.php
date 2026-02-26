{{-- resources/views/invitations/accept.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 border rounded">
    <h2 class="text-xl font-bold mb-4">Invitation to join {{ $invitation->colocation->name }}</h2>

    <form action="{{ route('invitations.accept', $invitation->token) }}" method="POST">
        @csrf
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Accept Invitation</button>
    </form>

    <form action="{{ route('invitations.decline', $invitation->token) }}" method="POST" class="mt-2">
        @csrf
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Decline Invitation</button>
    </form>
</div>
@endsection