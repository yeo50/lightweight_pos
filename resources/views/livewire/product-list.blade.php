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
    public function mount()
    {
        $this->team = Auth::user()->currentTeam;
        $this->user_id = $this->team->user_id;

        $this->products = Product::where('team_id', $this->team->id)->get();
        $this->checked = false;
        $this->selected = false;
        $this->filter = false;
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
}; ?>

<div class="mt-4">

    <div class=" border border-gray-300 rounded-xl">
        <div class="flex justify-end py-4 pe-4">
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
                <div><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                </div>
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
                    <th class="text-start py-2 px-3 ">No.</th>
                    <th class="text-start py-2 px-3">Name</th>
                    <th class="text-start py-2 px-3">Price</th>
                    <th class="text-start py-2 px-3">Quantity</th>
                </thead>
                @foreach ($products as $key => $item)
                    <tr :key={{ $key }}>
                        <td class="ps-3 w-8">
                            <input type="checkbox" wire:model="ids" value="{{ $item->id }}" class="bg-gray-300"
                                wire:click="selectedItem">
                        </td>

                        <td class=" text-start px-3 py-2 ">{{ $key + 1 }}</td>
                        <td class=" text-start px-3 py-2">{{ $item->name }}</td>
                        <td class=" text-start px-3 py-2">{{ $item->price }}</td>
                        <td class=" text-start px-3 py-2">{{ $item->quantity }}</td>
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
