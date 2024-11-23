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
    public $receipt;
    public $payment_method;
    public function mount()
    {
        $this->total = 0;
        $user = Auth::user();
        $this->team_id = $user->currentTeam->id;
        $this->payment_method = 'cash';
    }
    public function paymentCash()
    {
        $this->payment_method = 'cash';
    }
    public function paymentMobile()
    {
        $this->payment_method = 'mobile banking';
    }

    public function barcodeDetected()
    {
        $product = Product::where('team_id', $this->team_id)
            ->where('barcode', $this->barcode)
            ->orWhere('name', 'LIKE', '%' . $this->barcode . '%')
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
                    'quantity' => $product->quantity,
                ];
                $this->counts[] = 1;

                $total = $product->price * 1;
                $this->total += $total;
            }
        }
        $this->barcode = '';
    }

    public function reCount($key)
    {
        if ($this->counts[$key] < $this->products[$key]['quantity']) {
            $prevTotal = $this->products[$key]['price'] * $this->products[$key]['count'];
            $this->total -= $prevTotal;
            $this->products[$key]['count'] = $this->counts[$key];
            $total = $this->products[$key]['price'] * $this->products[$key]['count'];
            $this->total += $total;
        }
    }
    public function cancel($key)
    {
        $product = $this->products[$key];
        $prevTotal = $product['price'] * $product['count'];
        //remove from array
        unset($this->products[$key]);
        $this->total -= $prevTotal;
        //rearranged array items
        $this->products = array_values($this->products);
    }
    public function completePayment()
    {
        DB::transaction(function () {
            $newTransaction['team_id'] = $this->team_id;
            $newTransaction['transaction_no'] = 'TRX-' . fake()->creditCardNumber();
            $newTransaction['amount'] = $this->total;
            $newTransaction['payment_method'] = $this->payment_method;

            $transaction = Transaction::create($newTransaction);
            $this->receipt = $transaction->id;
            foreach ($this->products as $key => $value) {
                $product = Product::where('team_id', $this->team_id)
                    ->where('barcode', $value['barcode'])
                    ->first();

                $newQuantity = $product->quantity - $value['count'];
                $product->quantity = $newQuantity;

                $newSale['team_id'] = $this->team_id;
                $newSale['transaction_id'] = $transaction->id;
                $newSale['name'] = $product->name;
                $newSale['count'] = $value['count'];
                $newSale['price'] = $product->price;
                $newSale['total'] = $product->price * $value['count'];
                $sale = Sale::create($newSale);
                $product->update();
            }
        });
        $this->products = [];
        return redirect()->route('sales.receipt', $this->receipt);
    }
}; ?>

<div>

    <div class=" px-4 md:grid md:grid-cols-[minmax(0,1fr)_250px]">
        <div>
            <div x-data="{ scan: 'barcode' }">
                <div class="flex border-4 ms-2 w-fit bg-gray-200 rounded-lg">
                    <div @click= "scan = 'barcode' " class=" font-semibold  text-[#222] px-3 py-2"
                        :class="scan === 'barcode' ? '' : 'shadow-lg cursor-pointer bg-white  rounded-lg'">
                        Scan Barcode</div>
                    <div @click="scan = 'name'" class=" font-semibold text-[#222] px-3 py-2  "
                        :class="scan === 'name' ? '' : 'shadow-lg cursor-pointer bg-white  rounded-lg'">
                        Enter Manually
                    </div>
                </div>
                <div class="flex justify-between ">
                    <form x-show="scan  === 'barcode'" wire:submit="barcodeDetected">
                        <div class="mt-3 space-x-2 flex px-4 py-2">
                            <div wire:loading.remove>
                                <input type="text" autofocus wire:input.debounce.500ms="barcodeDetected"
                                    wire:model.debounce.100ms="barcode" id="barcode" placeholder="Scan Barcode"
                                    class="rounded-xl min-w-72  inline-block bg-gray-200">

                            </div>
                            <div class="loader" wire:loading></div>
                            <div data-content ="Place the cursor in input box & scan the barcode. Best tools Scan_It .Install from Play store & its website "
                                class="inline-flex items-center font-bold text-xl cursor-pointer relative
                            hover:before:content-[''] hover:before:absolute hover:before:left-4 before:scale-x-0 hover:before:scale-x-100
                            hover:transition-all hover:before:duration-500 before:opacity-0 hover:before:opacity-100 before:bg-gray-700
                            hover:before:w-5 hover:before:h-5 hover:before:rotate-45
                             hover:after:content-[attr(data-content)] hover:after:w-80 hover:after:h-20
                            after:absolute after:left-6 after:-top-4 after:scale-x-0 after:transition-all after:duration-150  hover:after:scale-x-100  after:text-xs after:tracking-wide hover:after:px-3 hover:after:py-2 after:bg-gray-700 after:text-white after:font-medium ">
                                ?
                            </div>
                        </div>
                    </form>
                    <form x-show="scan === 'name'" wire:submit="barcodeDetected" class="mt-3 px-4 py-2">
                        <input type="text" wire:model="barcode" placeholder="Enter Name Or Barcode"
                            class="rounded-xl min-w-72  inline-block bg-gray-200">
                        <button type="submit"
                            class="ms-2 max-md:mt-2 text-white rounded-xl shadow-sm font-semibold px-3 py-2 bg-indigo-500 hover:bg-indigo-400 focus:bg-indigo-400 active:bg-indigo-600 ">Submit</button>
                    </form>

                </div>
            </div>


            <table class="w-[90%] border px-4 py-6 me-auto ms-4 mt-8 ">
                <thead class="border-b bg-gray-200">
                    <th class="max-sm:px-1 max-sm:font-semibold px-3 py-2 text-start">No.</th>
                    <th class="max-sm:px-1 max-sm:font-semibold px-3 py-2 text-start">Name</th>
                    <th class="max-sm:px-1 max-sm:font-semibold px-3 py-2 text-start">Price</th>
                    <th class="max-sm:px-1 max-sm:font-semibold px-3 py-2 text-start">Quantity</th>
                    <th class="max-sm:px-1 max-sm:font-semibold px-3 py-2 text-start">Costs</th>
                    <th></th>

                </thead>
                @if ($products)
                    @foreach ($products as $key => $item)
                        <tr :key={{ $key }} x-data="{
                            conditionOrigin: true,
                            conditionChange: false,
                            conditionExceed: false,
                            exceedProduct: 1,
                        }">
                            <td class="px-3 py-2 text-start">{{ $key + 1 }}</td>
                            <td class="px-3 py-2 text-start">{{ $item['name'] }}</td>

                            <td class="px-3 py-2 text-start">{{ $item['price'] }}</td>
                            <td x-show="conditionOrigin" class="px-3 py-2 text-start cursor-pointer"
                                @click="conditionChange = true; conditionOrigin = false">
                                {{ $item['count'] }}
                            </td>
                            <td x-show="conditionChange">
                                <input type="number" wire:model.defer="counts.{{ $key }}"
                                    x-model="exceedProduct"
                                    x-on:keydown.enter="if (exceedProduct > {{ $item['quantity'] }}) {
                                               conditionExceed = true;
                                               conditionOrigin = false;
                                           } else {
                                            conditionOrigin = true;
                                            $wire.reCount({{ $key }});
                                          }; conditionChange = false;"
                                    class="inline-block min-w-10 w-20 bg-gray-200">

                            </td>
                            <td x-show="conditionExceed" @click="conditionOrigin = true; conditionExceed = false"
                                class="px-3 py-2 text-start cursor-pointer"> Maximum quantity {{ $item['quantity'] }}
                                only
                            </td>
                            <td class="px-3 py-2 text-start">{{ $item['price'] * $item['count'] }} $</td>
                            <td class="cursor-pointer" wire:click="cancel({{ $key }})"><svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
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
        </div>
        <aside class=" p-4 max-md:mt-6">
            <h1 class="text-center text-xl font-bold my-2">Summary</h1>
            <div class="flex justify-between py-2  font-semibold text-lg">
                <span>Subtotal</span>
                <span>{{ $total }} $</span>
            </div>
            <div class="flex justify-between  py-2 font-semibold text-lg">
                <span>Discount</span>
                <span>0 $</span>
            </div>
            <div class="flex justify-between  py-2 font-semibold text-xl">
                <span class="text-green-600">Total</span>
                <span>{{ $total }} $</span>
            </div>
            <div x-data="{ payment: false, method: 'Cash' }" class="cursor-pointer py-2">
                <div class="flex space-x-1" @click="payment = !payment" class="cursor-pointer py-2">
                    <span>Payment Method-</span><span x-text='method'></span> <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div x-show="payment" class="border">
                    <p x-on:click="payment = false; method = 'Cash'; $wire.paymentCash()"
                        class="ps-3 py-2 border hover:border-blue-400">Cash</p>
                    <p x-on:click="payment = false;  method = 'Mobile Banking'; $wire.paymentMobile()"
                        class="ps-3 py-2 border hover:border-blue-400">Mobile Banking
                    </p>
                </div>
            </div>
            <div class="mt-2">
                <x-anchor-button wire:click="completePayment" class="py-3 cursor-pointer">Complete
                    Payment</x-anchor-button>
            </div>
        </aside>
    </div>
    <div>





    </div>
