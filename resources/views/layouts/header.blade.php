<header class="bg-blue-600 text-white p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">EasyColoc</h1>

        <nav class="flex items-center space-x-4">
            <a href="{{ url('/') }}" class="hover:underline">Home</a>

            @auth
                {{-- Link to create a new colocation --}}
                <a href="{{ route('colocations.create') }}" class="hover:underline">Create Colocation</a>

                {{-- Link to my colocations --}}
                <a href="{{ route('colocations.my') }}" class="hover:underline">My Colocations</a>

                {{-- Dashboard / Profile --}}
                <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                <span class="ml-4">Hi, {{ auth()->user()->name }}</span>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="hover:underline">Login</a>
                <a href="{{ route('register') }}" class="hover:underline">Register</a>
            @endguest
        </nav>
    </div>
</header>