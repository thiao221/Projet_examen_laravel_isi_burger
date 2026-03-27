@extends('layouts.app')

@section('title', 'Commande #' . $order->id)

@section('content')

    <div class="max-w-3xl mx-auto">

        {{-- En-tête --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Commande #{{ $order->id }}
            </h1>
            <a href="{{ route('admin.orders.index') }}"
               class="text-gray-500 hover:text-gray-700">
                ← Retour aux commandes
            </a>
        </div>

        {{-- Infos client --}}
        <div class="bg-white rounded-lg shadow p-6 mb-4">
            <h2 class="font-bold text-gray-700 mb-3">Informations client</h2>
            <p><span class="text-gray-500">Nom :</span> {{ $order->user->name }}</p>
            <p><span class="text-gray-500">Email :</span> {{ $order->user->email }}</p>
            <p><span class="text-gray-500">Date :</span> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
        </div>

        {{-- Articles commandés --}}
        <div class="bg-white rounded-lg shadow p-6 mb-4">
            <h2 class="font-bold text-gray-700 mb-3">Articles commandés</h2>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-400">
                                {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA × {{ $item->quantity }}
                            </p>
                        </div>
                        <span class="font-bold text-orange-600">
                            {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                @endforeach

                {{-- Total --}}
                <div class="flex justify-between items-center pt-2">
                    <span class="font-bold text-lg">Total</span>
                    <span class="font-bold text-xl text-orange-600">
                        {{ number_format($order->total, 0, ',', ' ') }} FCFA
                    </span>
                </div>
            </div>
        </div>

        {{-- Changer le statut --}}
        @if($order->status !== 'annulee' && $order->status !== 'payee')
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-bold text-gray-700 mb-3">Changer le statut</h2>

                <form method="POST" action="{{ route('admin.orders.status', $order) }}"
                      class="flex gap-4 items-center">
                    @csrf @method('PATCH')

                    <select name="status" class="border rounded px-3 py-2 flex-1">
                        @foreach(App\Models\Order::STATUTS as $key => $label)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </form>

                {{-- Statut actuel --}}
                @php $couleur = App\Models\Order::COULEURS[$order->status]; @endphp
                <p class="mt-3 text-sm text-gray-500">
                    Statut actuel :
                    <span class="bg-{{ $couleur }}-100 text-{{ $couleur }}-700 px-2 py-1 rounded font-semibold">
                        {{ App\Models\Order::STATUTS[$order->status] }}
                    </span>
                </p>
            </div>
        @endif
    </div>

@endsection
