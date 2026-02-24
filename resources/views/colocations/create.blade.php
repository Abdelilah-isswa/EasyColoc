<x-app-layout>
    <div class="max-w-lg mx-auto mt-6">

        <form method="POST" action="{{ route('colocations.store') }}">
            @csrf

            <label class="block mb-2">Colocation Name</label>
            <input type="text" name="name" required class="border p-2 w-full">

            <button class="bg-blue-500 text-white px-4 py-2 mt-4">
                Create
            </button>
        </form>

    </div>
</x-app-layout>