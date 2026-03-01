{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - EasyColoc</title>
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
                    <a href="{{ route('login') }}" class="hover:text-blue-200">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50">
                        Register
                    </a>
                </nav>
            </div>
        </div>
    </header>

    {{-- Forgot Password Form --}}
    <main class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            {{-- Card --}}
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Reset Password</h2>
                
                {{-- Info Text --}}
                <p class="text-gray-600 text-sm mb-6">
                    Forgot your password? No problem. Just enter your email address and we'll send you a password reset link.
                </p>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
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

                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-2 px-4 rounded-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Send Reset Link
                    </button>

                    {{-- Back to Login --}}
                    <p class="text-center text-sm text-gray-600 mt-4">
                        Remember your password? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">
                            Back to Login
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