<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Team;
new class extends Component {
    public $user_id;
    public $team;
    public $search_value;
    public $filter;
    public $products;
    public $ids = [];
    public $checked;
    public $selected;
    public $refresh;
    public $number;
    public $price_action;
    public $min;
    public $max;
    public $filterPriceRange;
    public $sortBoo;
    public function mount()
    {
        $this->team = Auth::user()->currentTeam;
        $this->user_id = $this->team->user_id;

        $this->products = Product::where('team_id', $this->team->id)->get();
        $this->checked = false;
        $this->selected = false;
        $this->filter = false;
        $this->price_action = false;
        $this->filterPriceRange = false;
    }
    public function search()
    {
        $this->products = Product::where('team_id', $this->team->id)
            ->where('name', 'LIKE', '%' . $this->search_value . '%')
            ->get();
        $this->filter = true;

        return $this;
    }
    public function searchCancel()
    {
        $this->products = Product::where('team_id', $this->team->id)->get();
        $this->filter = false;
        $this->search_value = '';
        return $this;
    }
    public function selectedItem()
    {
        if (!empty($this->ids)) {
            $this->selected = true;
        } else {
            $this->selected = false;
            $this->checked = false;
        }
    }
    public function bulkActionChecked()
    {
        if (!$this->checked) {
            $this->checked = true;
            $this->ids = $this->products->pluck('id');
            $this->selected = true;
        } else {
            $this->checked = false;
            $this->selected = false;
            $this->ids = [];
        }
    }
    public function bulkDeleteAll()
    {
        if (!empty($this->ids)) {
            Product::whereIn('id', $this->ids)->delete();

            $this->ids = [];
            $this->checked = false;
            $this->selected = false;
            return redirect()->route('products.index');
        } else {
            session()->flash('error', 'No products selected for deletion.');
        }
    }
    public function priceModified($value, $method)
    {
        foreach ($this->products as $key => $item) {
            if ($value == 'increase') {
                if ($method == 'percentage') {
                    $percent = ($item->price * $this->number) / 100;
                    $item->price += $percent;
                    $item->price = round($item->price);
                    $item->update();
                } else {
                    $item->price += $this->number;
                    $item->update();
                }
            } else {
                if ($method == 'percentage') {
                    $percent = ($item->price * $this->number) / 100;
                    $item->price -= $percent;
                    $item->price = round($item->price);
                    $item->update();
                } else {
                    $item->price -= $this->number;
                    $item->update();
                }
            }
        }
        return $this->price_action = false;
    }
    public function filterRange()
    {
        $this->products = Product::whereBetween('price', [$this->min, $this->max])->get();
        $this->filterPriceRange = true;
        return $this;
    }
    public function sortBy($name)
    {
        if ($name == 'price') {
            $this->sortBoo = !$this->sortBoo;

            $sortedProducts = $this->products->sortBy('price');
            $reversedProducts = $sortedProducts->reverse();
            $this->products = $this->sortBoo ? $sortedProducts : $reversedProducts;
        }
        if ($name == 'number') {
            $sortedProducts = $this->products->sortBy('id');
            $this->products = $sortedProducts;
        }
    }
}; ?>

<div class="mt-4">

    <div x-data="{ rangeFilter: false }" class=" border border-gray-300 rounded-xl">
        <div x-data="{ price: false, subLabel: false, value: '', priceAction: @entangle('price_action').live, method: '' }" class="flex justify-end py-4 pe-4 relative">
            <div class="flex justify-between items-center space-x-3">
                <div class="  items-center w-fit rounded-2xl relative ">
                    <form wire:submit="search">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-6 inline-block text-gray-500 translate-x-10 ">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>

                        <input type="text" placeholder="Search" wire:model="search_value"
                            class="ps-10 inline-block bg-gray-100 h-8 rounded-xl  ">
                    </form>
                </div>
                <div x-data="{ range: false }" @click="range=!range" @click.outside="range = false"
                    class="cursor-pointer relative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    <div x-show="range" class="absolute top-10 right-0 w-40 h-fit bg-gray-800 text-white">
                        <ul>
                            <li @click="rangeFilter = true;" class="px-3 py-2 hover:bg-gray-500 select-none">Price Range
                            </li>
                            <li @click="rangeFilter = true;" class="px-3 py-2 hover:bg-gray-500 select-none">Quantity
                                Range</li>
                        </ul>
                    </div>
                </div>
                <div x-on:click="price = !price ; value = ''" @click.outside="price = false" class="cursor-pointer ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                    </svg>

                </div>

                <div x-show="price"
                    class="absolute right-0 top-16  h-fit min-w-40  z-10 bg-gray-800 rounded-sm text-white ">
                    <p @click="subLabel = true ; value='increase'"
                        class="px-3 py-2 select-none cursor-pointer hover:bg-gray-600">
                        <span class="pe-2" x-text="value == 'increase' ? '&lt;' : ''"></span>Increase Price

                    </p>

                    <p @click="subLabel = true;value='decrease'"
                        class="px-3 py-2 select-none cursor-pointer hover:bg-gray-600">
                        <span class="pe-2" x-text="value == 'decrease' ? '&lt;' : ''"></span>Decrease Price
                    </p>
                </div>
                <div x-show="subLabel"
                    class="absolute right-40 top-16 w-40 h-fit rounded-lg shadow-xl bg-gray-900 z-20 text-white">

                    <ul>
                        <li @click="price = false; subLabel = false; priceAction = true;  method='percentage' "
                            class="px-3 py-2 hover:bg-gray-600 rounded-lg">By
                            Percentage</li>
                        <li @click="price = false; subLabel = false; priceAction = true; method='value' "
                            class="px-3 py-2 hover:bg-gray-600 rounded-lg">By
                            Value</li>
                    </ul>
                </div>

            </div>
            <div x-show="priceAction" class="fixed inset-0 bg-gray-600/50 flex items-center justify-center">
                <div @click.outside="priceAction = false" class="min-w-80 h-40 bg-gray-900 p-4 text-white rounded-md">
                    <form wire:submit="priceModified(value,method)"
                        class="w-full flex items-center justify-center flex-col space-y-3">
                        <h1> <span x-text="value" class="capitalize"></span> Price By <span x-text="method"
                                class="capitalize"></span></h1>

                        <div>
                            <input type="number" wire:model="number"
                                class="bg-gray-200 mt-2 inline-block w-40 h-10 placeholder:text-xs placeholder:text-blue-600 text-black"
                                placeholder="Enter Percentage"> <button type="submit"
                                class="bg-violet-500 h-10 rounded-md shadow-sm shadow-white px-3 py-2">
                                Enter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- filter  --}}
        <div x-show="rangeFilter" class="flex justify-between px-4">
            <div class="py-2 px-3 border-t">
                Active Filter: Price
                @if ($filterPriceRange)
                    Between
                    <span>{{ $min }} $ and {{ $max }} $</span>
                @endif
                @if (!$filterPriceRange)
                    <label for="min">min</label> <input type="number" id="min" wire:model='min'
                        class="bg-gray-300 inline-block w-16 h-6">
                    <label for="max">max</label> <input type="number" id="max" wire:model="max"
                        class="bg-gray-300 inline-block w-16 h-6">
                    <x-button wire:click="filterRange">Filter</x-button>
                @endif
            </div>
            <div>
                <button @click="rangeFilter = false"> <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg></button>
            </div>
        </div>
        @if ($filter)
            <div class="flex justify-between px-4">
                <div class="py-2 px-3 border-t">
                    Active Filter: <span class="font-semibold">{{ $search_value }}</span>
                </div>
                <div>
                    <button wire:click="searchCancel"> <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg></button>
                </div>
            </div>
        @endif
        @if ($checked || $selected)
            <div class="flex justify-between py-4 px-4 border-t ">
                <div>{{ count($ids) }} selected</div>

                <x-button class="bg-red-500 hover:bg-red-400 focus:bg-red-400 active:bg-red-600"
                    wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE"
                    wire:click="bulkDeleteAll">Delete
                    Selected</x-button>
            </div>
        @endif

        <form action="">


            <table class="w-full ">

                <thead class="bg-gray-300  ">
                    <td class="ps-3 w-8"> <input type="checkbox" {{ $checked ? 'checked' : '' }}
                            wire:click="bulkActionChecked"></td>
                    <th wire:click="sortBy('number')" class="text-start py-2 px-3 cursor-pointer">No.</th>
                    <th class="text-start py-2 px-3">Name</th>
                    <th wire:click="sortBy('price')" class="text-start py-2 px-3 cursor-pointer">Price</th>
                    <th class="text-start py-2 px-3">Quantity</th>
                    <th class="text-start py-2 px-3">Barcode</th>
                </thead>
                @foreach ($products as $key => $item)
                    <tr :key={{ $key }}>
                        <td class="ps-3 w-8">
                            <input type="checkbox" wire:model="ids" value="{{ $item->id }}" class="bg-gray-300"
                                wire:click="selectedItem">
                        </td>

                        <td class=" text-start px-3 py-2 ">{{ $key + 1 }}</td>
                        <td class=" text-start px-3 py-2">{{ $item->name }}</td>
                        <td class=" text-start px-3 py-2">{{ $item->price }} $</td>
                        <td class=" text-start px-3 py-2">{{ $item->quantity }}</td>
                        <td class=" text-start px-3 py-2">{{ $item->barcode }}</td>
                        @can('edit-product', $item)
                            <td><a href="{{ route('products.edit', $item->id) }}"
                                    class="font-semibold text-violet-600">Edit</a></td>
                        @endcan

                    </tr>
                @endforeach

            </table>
        </form>
    </div>

</div>
