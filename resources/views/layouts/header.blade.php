<header class="bg-blue-600 text-white p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">EasyColoc</h1>

        <nav class="flex items-center space-x-4">
            <a href="{{ url('/') }}" class="hover:underline">Home</a>

            @auth
                {{-- Colocation Links --}}
                <a href="{{ route('colocations.create') }}" class="hover:underline">Create Colocation</a>
                <a href="{{ route('colocations.my') }}" class="hover:underline">My Colocations</a>

                @isset($colocation)
                    <a href="{{ route('colocations.expenses.history', $colocation) }}" class="hover:underline">
                        Expenses History
                    </a>

                    {{-- Statistics link --}}
                    <a href="{{ route('colocations.statistics', $colocation) }}" class="hover:underline">
                        Statistics
                    </a>
                @endisset

                {{-- Admin Panel --}}
                @if(auth()->user()->global_role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="hover:underline font-bold text-yellow-300">
                        Admin Panel
                    </a>
                @endif

                {{-- User Dropdown --}}
                <div class="relative ml-4 group">
                    <button class="hover:underline">Hi, {{ auth()->user()->name }}</button>
                    <div class="absolute right-0 mt-2 w-40 bg-white text-black rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                        @php
                            $profileLinks = [
                                ['route' => 'profile.show', 'label' => 'Profile'],
                                ['route' => 'profile.edit', 'label' => 'Edit Profile']
                            ];
                        @endphp
                        @foreach($profileLinks as $link)
                            <a href="{{ route($link['route']) }}" class="block px-4 py-2 hover:bg-gray-200">{{ $link['label'] }}</a>
                        @endforeach
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-200">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="hover:underline">Login</a>
                <a href="{{ route('register') }}" class="hover:underline">Register</a>
            @endguest
        </nav>
    </div>
</header>