{{-- resources/views/layouts/header.blade.php --}}
<header class="bg-blue-600 text-white p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">EasyColoc</h1>
        <nav>
            <a href="/" class="mr-4 hover:underline">Home</a>
            <a href="{{ route('colocations.create') }}" class="mr-4 hover:underline">Create Colocation</a>
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
        </nav>
    </div>
</header>