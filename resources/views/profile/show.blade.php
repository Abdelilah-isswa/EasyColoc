@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Your Profile</h1>

    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Reputation:</strong> {{ $user->reputation_score ?? 'N/A' }}</p>
    <p><strong>Role:</strong> {{ $user->global_role }}</p>

    <a href="{{ route('profile.edit') }}" class="text-blue-500 hover:underline mt-4 inline-block">
        Edit Profile
    </a>
</div>
@endsection