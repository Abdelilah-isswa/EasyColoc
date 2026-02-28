@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Historique des dépenses - {{ $colocation->name }}</h2>

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2 border">Date</th>
                <th class="px-4 py-2 border">Titre</th>
                <th class="px-4 py-2 border">Montant</th>
                <th class="px-4 py-2 border">Catégorie</th>
                <th class="px-4 py-2 border">Payeur</th>
                <th class="px-4 py-2 border">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $expense->date->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">{{ $expense->title }}</td>
                    <td class="px-4 py-2">{{ number_format($expense->amount, 2) }} €</td>
                    <td class="px-4 py-2">{{ $expense->category->name ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $expense->payeur->name }}</td>
                    <td class="px-4 py-2">
                        @if($expense->is_paid)
                            <span class="text-green-600 font-bold">Payé</span>
                        @else
                            <span class="text-red-600 font-bold">En attente</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center">Aucune dépense enregistrée</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $expenses->links() }}
    </div>
</div>
@endsection
