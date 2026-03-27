@extends('layouts.app')

@section('title', 'Gestion des Commandes')

@section('content')

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Gestion des Commandes</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Client</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Articles</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 font-bold text-gray-700">#{{ $order->id }}</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $order->items->count() }} article(s)
                    </td>

                    <td class="px-6 py-4 font-bold text-blue-600">
                        {{ number_format($order->total, 0, ',', ' ') }} FCFA
                    </td>

                    {{-- Badge statut --}}
                    <td class="px-6 py-4">
                        @php $couleur = App\Models\Order::COULEURS[$order->status]; @endphp
                        <span class="bg-{{ $couleur }}-100 text-{{ $couleur }}-700 px-2 py-1 rounded text-xs font-semibold">
                                {{ App\Models\Order::STATUTS[$order->status] }}
                            </span>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="bg-blue-100 text-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-200">
                            Détail
                        </a>
                        @if($order->status !== 'annulee' && $order->status !== 'payee')
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                                  onsubmit="return confirm('Annuler cette commande ?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-100 text-red-600 px-3 py-1 rounded text-sm hover:bg-red-200">
                                    Annuler
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        Aucune commande pour l'instant.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $orders->links() }}</div>
    </div>

@endsection
