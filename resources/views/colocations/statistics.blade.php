{{-- resources/views/colocations/statistics.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistics - ' . $colocation->name)

@section('content')
<h1 class="text-2xl font-bold mb-4">Statistics for {{ $colocation->name }}</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Expenses by Category --}}
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Expenses by Category</h2>
       @if(empty($expensesByCategory))
            <p>No expenses recorded.</p>
        @else
        <table class="w-full border border-gray-300 bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Category</th>
                    <th class="px-4 py-2 border">Total (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expensesByCategory as $category => $total)
                <tr>
                    <td class="px-4 py-2 border">{{ $category }}</td>
                    <td class="px-4 py-2 border font-bold">{{ number_format($total, 2) }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Expenses by Month --}}
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Expenses by Month</h2>
        @if( empty($expensesByMonth))
            <p>No expenses recorded.</p>
        @else
        <table class="w-full border border-gray-300 bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Month</th>
                    <th class="px-4 py-2 border">Total (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expensesByMonth as $month => $total)
                <tr>
                    <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</td>
                    <td class="px-4 py-2 border font-bold">{{ number_format($total, 2) }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
@endsection