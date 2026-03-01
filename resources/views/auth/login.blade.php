{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - EasyColoc</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Simple Header --}}
    <header class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="text-2xl font-bold">EasyColoc</a>
                <nav class="space-x-4">
                    <a href="{{ url('/') }}" class="hover:text-blue-200">Home</a>
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50">
                        Register
                    </a>
                </nav>
            </div>
        </div>
    </header>

    {{-- Login Form --}}
    <main class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            {{-- Login Card --}}
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Sign In</h2>
                <p class="text-gray-600 mb-6">Welcome back to EasyColoc</p>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="your@email.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center text-sm">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-gray-600">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-2 px-4 rounded-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Sign In
                    </button>

                    {{-- Register Link --}}
                    <p class="text-center text-sm text-gray-600 mt-4">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">
                            Register
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    {{-- Simple Footer --}}
    <footer class="bg-white border-t border-gray-200 py-4 text-center text-gray-600 text-sm">
        &copy; {{ date('Y') }} EasyColoc. All rights reserved.
    </footer>

</body>
</html>