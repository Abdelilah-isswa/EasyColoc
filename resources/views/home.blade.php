{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Welcome Section --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow-lg p-8 mb-8">
        <h1 class="text-3xl font-bold mb-2">Welcome to EasyColoc</h1>
        <p class="text-xl text-blue-100">Manage your colocations, expenses, and members easily.</p>
        
        @guest
        <div class="mt-6 flex gap-4">
            <a href="{{ route('login') }}" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-blue-50">Login</a>
            <a href="{{ route('register') }}" class="border-2 border-white text-white px-4 py-2 rounded hover:bg-white hover:text-blue-600">Register</a>
        </div>
        @endguest
    </div>

    @auth
    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('colocations.create') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow flex items-center">
            <div class="bg-blue-100 rounded-full p-3 mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Create New Colocation</h3>
                <p class="text-sm text-gray-600">Start a new shared space</p>
            </div>
        </a>

        <a href="{{ route('colocations.my') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow flex items-center">
            <div class="bg-green-100 rounded-full p-3 mr-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">My Colocations</h3>
                <p class="text-sm text-gray-600">View your colocations</p>
            </div>
        </a>
    </div>




 
 
    @endauth
</div>
@endsection