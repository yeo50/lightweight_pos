<x-app-layout>
    <x-slot:label>
        sales
    </x-slot>

    @if ($transaction && $sales)
        <div class="p-4">
            <div class="px-6 py-4 border border-black">
                <h1 class="text-center font-bold text-lg my-3">{{ Auth::user()->currentTeam->name }}</h1>
                <div class="flex justify-between">
                    <div>
                        <h1>Date : {{ $formattedDate }}</h1>
                        <h1>Cashier Name : {{ Auth::user()->name }}</h1>
                        <h1>Order No : {{ $transaction->transaction_no }}</h1>
                    </div>
                    <div>
                        <p>Payment Method : <span class="capitalize">{{ $transaction->payment_method ?? 'Cash' }}</span>
                        </p>
                    </div>
                </div>
                <div class="border-t border-dotted mt-3 border-2 "></div>
                <table class="w-full" x-data="{ count: 0 }">
                    <thead class="border-b ">
                        <th class="py-3 px-3 text-start">Qty </th>
                        <th class="py-3 px-3 text-start">Item Name </th>
                        <th class="py-3 px-3 text-start">Rate</th>
                        <th class="py-3 px-3 text-start">Amount</th>
                    </thead>
                    @foreach ($sales as $item)
                        <tr>
                            <td class="py-3 px-3 text-start" x-init="count += {{ $item->count }}">{{ $item->count }}</td>
                            <td class="py-3 px-3 text-start">{{ $item->name }}</td>
                            <td class="py-3 px-3 text-start">{{ $item->price }} $</td>
                            <td class="py-3 px-3 text-start">{{ $item->total }} $</td>
                        </tr>
                    @endforeach
                    <tr class="border-t ">
                        <td colspan="2" class="py-3 px-3 text-start text-xl font-bold border-e">Total Qty : <span
                                x-text="count"></span></td>
                        <td colspan="2" class="py-3 px-3 text-start font-bold text-xl">Total Amount
                            : {{ $transaction->amount }} $
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endif
    <div class="flex justify-between p-4">
        <x-anchor-button href="{{ route('sales.index') }}">Back</x-anchor-button>
        <x-button>Print</x-button>
    </div>
</x-app-layout>
