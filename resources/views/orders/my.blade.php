@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Mes Commandes</h1>

    @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-400 text-lg">Vous n'avez pas encore de commande.</p>
            <a href="{{ route('products.index') }}"
               class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Voir le catalogue
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow p-6">

                    {{-- En-tête de la commande --}}
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800">
                                Commande #{{ $order->id }}
                            </h3>
                            <p class="text-sm text-gray-400">
                                {{ $order->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>

                        {{-- Badge statut --}}
                        @php $couleur = App\Models\Order::COULEURS[$order->status]; @endphp
                        <span class="bg-{{ $couleur }}-100 text-{{ $couleur }}-700 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ App\Models\Order::STATUTS[$order->status] }}
                        </span>
                    </div>

                    {{-- Liste des articles --}}
                    <div class="border-t pt-4 space-y-2">
                        @foreach($order->items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">
                                    {{ $item->product->name }}
                                    <span class="text-gray-400">× {{ $item->quantity }}</span>
                                </span>
                                <span class="font-semibold">
                                    {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Total + Actions --}}
                    <div class="border-t mt-4 pt-4 flex justify-between items-center">
                        <span class="font-bold text-lg text-blue-600">
                            Total : {{ number_format($order->total, 0, ',', ' ') }} FCFA
                        </span>

                        @if($order->status === 'prete' || $order->status === 'payee')
                            <a href="{{ route('orders.invoice', $order) }}"
                               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                                Télécharger la facture
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif

@endsection
