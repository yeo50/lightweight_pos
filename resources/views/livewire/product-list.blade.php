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
    public $changeValue;
    public $price_action;
    public $min;
    public $max;
    public $filterPriceRange;
    public $sortBoo;
    public $notFound;
    public function mount()
    {
        $this->products = collect();
        $this->team = Auth::user()->currentTeam;
        $this->user_id = $this->team->user_id;
        $this->notFound = false;
        $products = Product::where('team_id', $this->team->id)->get();
        if ($products->isEmpty()) {
            $this->notFound = true;
        }
        $this->products = $products;

        $this->checked = false;
        $this->selected = false;
        $this->filter = false;
        $this->price_action = false;
        $this->filterPriceRange = false;
    }

    public function search()
    {
        if ($this->search_value !== null) {
            $this->products = Product::where('team_id', $this->team->id)
                ->where('name', 'LIKE', '%' . $this->search_value . '%')
                ->get();
            $this->filter = true;

            return $this;
        }
    }
    public function searchCancel()
    {
        $this->products = Product::where('team_id', $this->team->id)->get();
        $this->filter = false;
        $this->search_value = '';
        $this->notFound = false;
        $this->filterPriceRange = false;
        $this->min = '';
        $this->max = '';

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
    public function priceModified($change, $method)
    {
        Gate::authorize('isOwner', Auth::user());
        foreach ($this->products as $key => $item) {
            if ($change == 'increase') {
                if ($method == 'percentage') {
                    $percent = ($item->price * $this->changeValue) / 100;
                    $item->price += $percent;
                    $item->price = round($item->price);
                    $item->update();
                } else {
                    $item->price += $this->changeValue;
                    $item->update();
                }
            } else {
                if ($method == 'percentage') {
                    $percent = ($item->price * $this->changeValue) / 100;
                    $item->price -= $percent;
                    $item->price = round($item->price);
                    $item->update();
                } else {
                    $item->price -= $this->changeValue;
                    $item->update();
                }
            }
        }
        return $this->price_action = false;
    }
    public function filterRange($value)
    {
        if ($value === 'price') {
            $products = Product::where('team_id', $this->team->id)
                ->whereBetween('price', [$this->min, $this->max])
                ->get()
                ->collect();
            if ($products->isEmpty()) {
                $this->notFound = true;
            }
            $this->products = $products;
        }
        if ($value === 'quantity') {
            $products = Product::where('team_id', $this->team->id)
                ->whereBetween('quantity', [$this->min, $this->max])
                ->get()
                ->collect();
            if ($products->isEmpty()) {
                $this->notFound = true;
            }
            $this->products = $products;
        }

        $this->filterPriceRange = true;
    }
    public function rangeFilterCancel()
    {
        $this->products = Product::where('team_id', $this->team->id)->get();
        $this->notFound = false;
        $this->filterPriceRange = false;
        $this->min = '';
        $this->max = '';
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

<div class="mt-4 ">

    <div x-data="{ filter: @entangle('filter').live, rangeFilter: false, rangeFilterValue: '' }" class=" border border-gray-300 rounded-xl">
        <div class="w-full flex  justify-end py-4 pe-4 relative ">
            <div class="flex justify-between items-center space-x-3">
                <x-product-list-units.search-form />
                <x-product-list-units.range />

                @can('isOwner', Auth::user())
                    <x-product-list-units.price-action />
                @endcan
            </div>
        </div>
        {{-- filter  --}}
        <x-product-list-units.filter :filter="$filter" :filterPriceRange="$filterPriceRange" :search_value="$search_value" :min="$min"
            :max="$max" />
        @if ($checked || $selected)
            <div class="flex justify-between py-4 px-4 border-t ">
                <div>{{ count($ids) }} selected</div>

                <x-button class="bg-red-500 hover:bg-red-400 focus:bg-red-400 active:bg-red-600"
                    wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE" wire:click="bulkDeleteAll">Delete
                    Selected</x-button>
            </div>
        @endif


        <div>

            <x-product-items :products="$products" :notFound="$notFound" :checked="$checked" />
        </div>



    </div>

</div>
