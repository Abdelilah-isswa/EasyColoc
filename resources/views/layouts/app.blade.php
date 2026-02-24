{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EasyColoc')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

    {{-- Header --}}
    @include('layouts.header')

    {{-- Main content --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

</body>
</html>