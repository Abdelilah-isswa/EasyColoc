{{-- resources/views/components/header.blade.php --}}
<header class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo/Brand --}}
            <div class="flex-shrink-0">
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <span class="text-2xl font-bold tracking-tight">EasyColoc</span>
                    <span class="bg-blue-500 px-2 py-1 rounded-md text-xs font-semibold">Beta</span>
                </a>
            </div>

            {{-- Desktop Navigation --}}
            <nav class="hidden md:flex items-center space-x-6">
                <a href="{{ url('/') }}" 
                   class="hover:text-blue-200 transition-colors duration-200 {{ request()->is('/') ? 'font-semibold border-b-2 border-white' : '' }}">
                    Home
                </a>

                @auth
                    <a href="{{ route('colocations.create') }}" 
                       class="hover:text-blue-200 transition-colors duration-200 {{ request()->routeIs('colocations.create') ? 'font-semibold border-b-2 border-white' : '' }}">
                        Create Colocation
                    </a>
                    
                    <a href="{{ route('colocations.my') }}" 
                       class="hover:text-blue-200 transition-colors duration-200 {{ request()->routeIs('colocations.my') ? 'font-semibold border-b-2 border-white' : '' }}">
                        My Colocations
                    </a>
                    
                    <a href="#" class="hover:text-blue-200 transition-colors duration-200">Expenses</a>

                    @if(auth()->user() && auth()->user()->global_role === 'admin')
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-yellow-400 text-blue-900 px-3 py-1 rounded-md hover:bg-yellow-300 transition-colors duration-200 font-semibold">
                            Manage Panel
                        </a>
                    @endif

                    {{-- User Menu --}}
                    <div class="relative ml-4" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 focus:outline-none group">
                            <span class="text-sm">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 fill-current group-hover:text-blue-200" 
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" 
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                                      clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" 
                             @click.away="open = false"
                             x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            
                            <a href="#" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" 
                       class="hover:text-blue-200 transition-colors duration-200 {{ request()->routeIs('login') ? 'font-semibold border-b-2 border-white' : '' }}">
                        Login
                    </a>
                    
                    <a href="{{ route('register') }}" 
                       class="bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50 transition-colors duration-200 font-semibold">
                        Register
                    </a>
                @endguest
            </nav>

            {{-- Mobile menu button --}}
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="text-white hover:text-blue-200 focus:outline-none">
                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" fill-rule="evenodd" 
                              d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z" />
                        <path x-show="mobileMenuOpen" fill-rule="evenodd" 
                              d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 0 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 0 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div x-show="mobileMenuOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="md:hidden py-4 border-t border-blue-500">
            
            <div class="flex flex-col space-y-3">
                <a href="{{ url('/') }}" class="hover:text-blue-200 transition-colors duration-200">Home</a>

                @auth
                    <a href="{{ route('colocations.create') }}" class="hover:text-blue-200 transition-colors duration-200">Create Colocation</a>
                    <a href="{{ route('colocations.my') }}" class="hover:text-blue-200 transition-colors duration-200">My Colocations</a>
                    <a href="#" class="hover:text-blue-200 transition-colors duration-200">Expenses</a>

                    @if(auth()->user() && auth()->user()->global_role === 'admin')
                        <a href="{{ route('admin.users.index') }}" class="text-yellow-300 font-semibold">Manage Panel</a>
                    @endif

                    <div class="pt-4 border-t border-blue-500">
                        <span class="block text-sm">Hi, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="text-sm hover:text-blue-200">Logout</button>
                        </form>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="hover:text-blue-200 transition-colors duration-200">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-blue-200 transition-colors duration-200">Register</a>
                @endguest
            </div>
        </div>
    </div>
</header>

<style>
    [x-cloak] { display: none !important; }
</style>