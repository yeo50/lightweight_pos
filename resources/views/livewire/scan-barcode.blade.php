<?php

use Livewire\Volt\Component;
use App\Models\Product;

new class extends Component {
    public $barcode;
    public $count;
    public $products;
    public function mount()
    {
        $this->count = 0;
    }
    public function barcodeSearch()
    {
        dd($this->barcode);
    }
    public function barcodeDetected()
    {
        $this->products = Product::where('barcode', $this->barcode)->get();
        $this->count++;
        $this->barcode = '';
    }
}; ?>

<div>


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
            <form x-show="scan">
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
            <form x-show="manual" class="mt-3 px-4 py-2">
                <input type="text" class="rounded-xl min-w-40  inline-block bg-gray-200">
                <button type="submit"
                    class="ms-2 text-white rounded-xl shadow-sm font-semibold px-3 py-2 bg-indigo-400">Submit</button>
            </form>
        </div>

    </div>
    <table class="w-[90%] border px-4 py-6 me-auto ms-4 mt-8 ">
        <thead>
            <th class="px-3 py-2 text-start class="px-3 py-2 text-start"">No.</th>
            <th class="px-3 py-2 text-start">Name</th>
            <th class="px-3 py-2 text-start">Price</th>
            <th class="px-3 py-2 text-start">Quantity</th>
            <th class="px-3 py-2 text-start">Costs</th>

        </thead>
        @if ($products)
            @foreach ($products as $key => $item)
            @endforeach
            <tr>
                <td class="px-3 py-2 text-start">{{ $key + 1 }}</td>
                <td class="px-3 py-2 text-start">{{ $item->name }}</td>
                <td class="px-3 py-2 text-start">{{ $item->price }}</td>
                <td class="px-3 py-2 text-start">{{ $count }}</td>
                <td class="px-3 py-2 text-start">{{ $item->price * $count }}</td>
            </tr>
        @endif
    </table>


</div>
