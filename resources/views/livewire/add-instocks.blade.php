<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Instock;
use App\Models\Team;
new class extends Component {
    public $search_value;
    public $team;
    public $filter;
    public $products;
    public array $qtyCounts = [];
    public array $addedInstocks = [];
    public function mount()
    {
        $this->team = Auth::user()->currentTeam;
        $this->filter = false;
    }
    public function search()
    {
        $this->products = [];
        if ($this->search_value !== null) {
            if (is_numeric($this->search_value)) {
                $product = Product::where('team_id', $this->team->id)
                    ->Where('barcode', $this->search_value)
                    ->first();
                if ($product) {
                    $this->products[] = ['name' => $product->name, 'oldQuantity' => $product->quantity, 'id' => $product->id, 'newQuantity' => ''];
                    $this->search_value = '';
                }
            } else {
                $products = Product::where('team_id', $this->team->id)
                    ->where('name', 'like', '%' . $this->search_value . '%')
                    ->get();
                foreach ($products as $key => $value) {
                    $this->products[] = ['name' => $value->name, 'oldQuantity' => $value->quantity, 'id' => $value->id, 'newQuantity' => ''];
                }

                $this->filter = true;
                return $this;
            }
        }
    }
    public function searchCancel()
    {
        $this->filter = false;
        $this->search_value = '';
        return $this;
    }
    public function addQuantity($key)
    {
        $oldQuantity = $this->products[$key]['oldQuantity'];
        $newQuantity = $oldQuantity + $this->qtyCounts[$key];
        $this->products[$key]['newQuantity'] = $newQuantity;
        $this->addedInstocks[] = ['id' => $this->products[$key]['id'], 'name' => $this->products[$key]['name'], 'quantity' => $this->qtyCounts[$key], 'newQuantity' => $newQuantity];
        $this->qtyCounts[$key] = 0;
        return $this;
    }
    public function instockAddedSummary()
    {
        if (!empty($this->addedInstocks)) {
            DB::transaction(function () {
                foreach ($this->addedInstocks as $key => $value) {
                    Product::where('team_id', $this->team->id)
                        ->where('id', $value['id'])
                        ->update(['quantity' => $value['newQuantity']]);
                    Instock::create(['team_id' => $this->team->id, 'product_id' => $value['id'], 'quantity' => $value['quantity']]);
                }
            });
            return redirect()->route('products.index');
        }
    }
};
?>
<div class="grid grid-cols-[minmax(0,1fr)_250px] p-4">
    <div>
        <div class="border  p-4 rounded-lg">
            <div class="flex justify-end my-3">
                <form wire:submit="search">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-6 inline-block text-gray-500 translate-x-10 ">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" placeholder="Enter Barcode or Name" wire:model="search_value"
                        class="ps-10 inline-block bg-gray-100 h-8 rounded-xl  ">
                </form>
            </div>
            @if ($filter)
                <div class="flex justify-between items-center py-3 border-y">
                    <div>
                        <h1>Active Filter : <span class="text-lg font-semibold">{{ $search_value }}</span></h1>
                    </div>
                    <div>
                        <button wire:click="searchCancel"> <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg></button>
                    </div>
                </div>
            @endif


            <table class="w-full">
                <thead>
                    <tr>
                        <td class="px-2 py-4 text-start font-bold">No.</td>
                        <td class="px-2 py-4 text-start font-bold">Name</td>
                        <td class="px-2 py-4 text-start font-bold">Old Quantity</td>
                        <td class="px-2 py-4 text-start font-bold">Add Quantity</td>
                        <td class="px-2 py-4 text-start font-bold">New Quantity</td>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($products))
                        @foreach ($products as $key => $item)
                            <tr :key={{ $key }} x-data="{ addQty: false }">
                                <td class="text-start p-2">{{ $key }}</td>
                                <td class="text-start p-2">{{ $item['name'] }}</td>
                                <td class="text-start p-2">{{ $item['oldQuantity'] }}</td>
                                <td @click="addQty =true" class="text-start p-2 cursor-pointer"
                                    :class="addQty ? 'hidden' : ''">add</td>
                                <td x-show="addQty">

                                    <input type="number" wire:model.defer="qtyCounts.{{ $key }}"
                                        x-on:keydown.enter="addQty = false; $wire.addQuantity({{ $key }})"
                                        class="inline-block min-w-10 w-20 bg-gray-200">

                                </td>
                                <td class="text-start p-2">{{ $item['newQuantity'] }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

        </div>
    </div>
    <aside class=" h-40 px-4 py-2 ">
        <h1 class="text-center text-lg my-2 font-semibold">Added Instock Summary</h1>
        <div class="flex justify-between my-2">
            <div>Name</div>
            <div>Qty</div>
        </div>
        @foreach ($addedInstocks as $item)
            <div class="flex justify-between">
                <div>{{ $item['name'] }}</div>
                <div>{{ $item['quantity'] }}</div>
            </div>
        @endforeach
        <div class="px-8 flex justify-between items-center">
            <button>Cancel</button>
            <button wire:click="instockAddedSummary"
                class="px-4 py-2 bg-green-600 rounded-lg shadow-lg text-gray-100 ">Save</button>
        </div>
    </aside>
</div>
