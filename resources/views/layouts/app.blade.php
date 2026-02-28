{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EasyColoc')</title>
    {{-- Use the latest Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Optional: Add custom styles if needed --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">

    {{-- Header --}}
    @include('layouts.header')

    {{-- Main content --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Alpine.js for dropdown functionality --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>