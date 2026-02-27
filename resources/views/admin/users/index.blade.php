@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">User Management</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">Name</th>
                <th class="border px-4 py-2 text-left">Email</th>
                <th class="border px-4 py-2 text-left">Status</th>
                <th class="border px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="border px-4 py-2">{{ $user->name }}</td>
                <td class="border px-4 py-2">{{ $user->email }}</td>
                <td class="border px-4 py-2">
                    @if($user->is_banned)
                        <span class="text-red-600 font-bold">Banned</span>
                    @else
                        <span class="text-green-600 font-bold">Active</span>
                    @endif
                </td>
                <td class="border px-4 py-2 space-x-2">
                    @if(!$user->is_banned)
                    <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Ban
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            Unban
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection