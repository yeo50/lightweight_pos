<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Sale;

new class extends Component {
    public $barcode;

    public $counts = [];
    public $total;
    public $team_id;
    public $products = [];
    public function mount()
    {
        $this->total = 0;
        $user = Auth::user();
        $currentTeamId = $user->currentTeam->id;

        $teamIds = [];
        $teams = $user->allTeams();

        foreach ($teams as $key => $value) {
            $teamIds[] = $value->id;
        }
        $keyId = array_search($currentTeamId, $teamIds);
        $this->team_id = $teamIds[$keyId];
    }

    public function barcodeDetected()
    {
        $user = Auth::user();
        $currentTeamId = $user->currentTeam->id;

        $teamIds = [];
        $teams = $user->allTeams();

        foreach ($teams as $key => $value) {
            $teamIds[] = $value->id;
        }
        $keyId = array_search($currentTeamId, $teamIds);

        if ($keyId !== false) {
            $teamId = $teamIds[$keyId];

            $product = Product::where('team_id', $teamId)
                ->where('barcode', $this->barcode)
                ->first();

            if ($product) {
                $existingProductKey = collect($this->products)->search(function ($item) use ($product) {
                    return $item['barcode'] == $product->barcode;
                });
                if ($existingProductKey !== false) {
                    $existingProduct = $this->products[$existingProductKey];
                    $oldTotal = $existingProduct['price'] * $existingProduct['count'];
                    $this->total -= $oldTotal;

                    $this->products[$existingProductKey]['count']++;
                    $count = $this->products[$existingProductKey]['count'];
                    $total = $existingProduct['price'] * $count;
                    $this->total += $total;
                } else {
                    $this->products[] = [
                        'name' => $product->name,
                        'price' => $product->price,
                        'barcode' => $product->barcode,
                        'count' => 1,
                    ];
                    $this->counts[] = 1;

                    $total = $product->price * 1;
                    $this->total += $total;
                }
            }
            $this->barcode = '';
        }
    }

    public function reCount($key)
    {
        $prevTotal = $this->products[$key]['price'] * $this->products[$key]['count'];
        $this->total -= $prevTotal;
        $this->products[$key]['count'] = $this->counts[$key];
        $total = $this->products[$key]['price'] * $this->products[$key]['count'];
        $this->total += $total;
    }
    public function cancel($key)
    {
        $product = $this->products[$key];
        $prevTotal = $product['price'] * $product['count'];

        unset($this->products[$key]);
        $this->total -= $prevTotal;
        $this->products = array_values($this->products);
    }
    public function completePayment()
    {
        $newTransaction['team_id'] = $this->team_id;
        $newTransaction['transaction_no'] = 'TRX-' . fake()->creditCardNumber();
        $newTransaction['amount'] = $this->total;
        $transaction = Transaction::create($newTransaction);
        foreach ($this->products as $key => $value) {
            $product = Product::where('team_id', $this->team_id)
                ->where('barcode', $value['barcode'])
                ->first();

            $newQuantity = $product->quantity - $value['count'];
            $product->quantity = $newQuantity;

            DB::transaction(function () use ($product, $transaction, $value) {
                $newSale['team_id'] = $this->team_id;
                $newSale['transaction_id'] = $transaction->id;
                $newSale['name'] = $product->name;
                $newSale['count'] = $value['count'];
                $newSale['price'] = $product->price;
                $newSale['total'] = $product->price * $value['count'];
                $sale = Sale::create($newSale);
                $product->update();
            });
        }
        $this->products = [];
    }
}; ?>

<div>

    <h1> 8836000165850</h1>
    <h1>8836000189078</h1>
    <h1> 8836000192443</h1>

    <div class=" px-4">
        <div x-data="{ scan: true, manual: false }">
            <div class="flex border-4 w-fit bg-gray-200 rounded-lg">
                <div @click= "scan = true ; manual = false" class=" font-semibold  text-[#222] px-3 py-2"
                    :class="manual ? 'shadow-lg cursor-pointer bg-white  rounded-lg' : ''">
                    Scan Barcode</div>
                <div @click="scan = false; manual = true;" class=" font-semibold text-[#222] px-3 py-2  "
                    :class="scan ? 'shadow-lg cursor-pointer bg-white  rounded-lg' : ''">
                    Enter Manually
                </div>
            </div>
            <form x-show="scan" wire:submit="barcodeDetected">
                <div class="mt-3 space-x-2 flex px-4 py-2">
                    <div>
                        <input type="text" autofocus wire:input.debounce.500ms="barcodeDetected"
                            wire:model.debounce.100ms="barcode" id="barcode"
                            class="rounded-xl min-w-40  inline-block bg-gray-200">
                    </div>
                    <div data-content ="Place the cursor in input box & scan the barcode. Best tools Scan_It .Install from Play store & its website "
                        class="inline-flex items-center font-bold text-xl cursor-pointer relative hover:after:content-[attr(data-content)] hover:after:w-80 hover:after:h-20
                        after:absolute after:left-4 after:top-0 after:text-xs after:tracking-wide hover:after:px-3 hover:after:py-2 after:bg-gray-700 after:text-white after:font-medium ">
                        ?
                    </div>
                </div>

            </form>
            <form x-show="manual" wire:submit="barcodeDetected" class="mt-3 px-4 py-2">
                <input type="text" wire:model="barcode" class="rounded-xl min-w-40  inline-block bg-gray-200">
                <button type="submit"
                    class="ms-2 text-white rounded-xl shadow-sm font-semibold px-3 py-2 bg-indigo-500 hover:bg-indigo-400 focus:bg-indigo-400 active:bg-indigo-600 ">Submit</button>
            </form>
        </div>

    </div>
    <table class="w-[90%] border px-4 py-6 me-auto ms-4 mt-8 ">
        <thead class="border-b bg-gray-200">
            <th class="px-3 py-2 text-start">No.</th>
            <th class="px-3 py-2 text-start">Name</th>
            <th class="px-3 py-2 text-start">Price</th>
            <th class="px-3 py-2 text-start">Quantity</th>
            <th class="px-3 py-2 text-start">Costs</th>
            <th></th>

        </thead>
        @if ($products)
            @foreach ($products as $key => $item)
                <tr :key={{ $key }} x-data="{ changeCount: false }">
                    <td class="px-3 py-2 text-start">{{ $key + 1 }}</td>
                    <td class="px-3 py-2 text-start">{{ $item['name'] }}</td>
                    <td class="px-3 py-2 text-start">{{ $item['price'] }}</td>
                    <td @click="changeCount = true ; " class="px-3 py-2 text-start cursor-pointer"
                        :class="changeCount ? 'hidden' : ''">
                        {{ $item['count'] }} </td>
                    <td x-show="changeCount">

                        <input type="number" wire:model.defer="counts.{{ $key }}"
                            x-on:keydown.enter="changeCount = false; $wire.reCount({{ $key }})"
                            class="inline-block min-w-10 w-20 bg-gray-200">

                    </td>
                    <td class="px-3 py-2 text-start">{{ $item['price'] * $item['count'] }} $</td>
                    <td class="cursor-pointer" wire:click="cancel({{ $key }})"><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </td>
                </tr>
            @endforeach
            <tr class="border-t">
                <td colspan ="4" class="px-3 py-2 text-start text-lg font-semibold">Total</td>
                <td class="px-3 py-2 text-start border-s text-lg font-semibold">{{ $total }} $</td>
            </tr>
        @endif

    </table>
    <div class="flex w-[90%] justify-end px-4 py-6">
        <x-anchor-button wire:click="completePayment" class="py-3 cursor-pointer">Complete Payment</x-anchor-button>
    </div>


</div>
