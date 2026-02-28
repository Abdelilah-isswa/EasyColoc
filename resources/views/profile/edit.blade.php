@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Profile</h1>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" value="{{ $user->name }}" class="border p-1 w-full">
        </div>

        <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="border p-1 w-full">
        </div>

        <div class="mb-2">
            <label>Password (leave blank to keep current)</label>
            <input type="password" name="password" class="border p-1 w-full">
        </div>

        <div class="mb-2">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="border p-1 w-full">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2">Save</button>
    </form>

    <form action="{{ route('profile.destroy') }}" method="POST" class="mt-4">
        @csrf
        @method('DELETE')
        <label>Confirm password to delete account</label>
        <input type="password" name="password" class="border p-1 w-full mb-2">
        <button type="submit" class="bg-red-500 text-white px-4 py-2">Delete Account</button>
    </form>
</div>
@endsection