<x-app-layout>
    <x-slot:label>
        products
    </x-slot>
    <div class="p-8">
        <p class="text-sm text-gray-600 font-semibold">Products &gt; All</p>

        <x-session></x-session>
        <div class="min-w-[100%] flex justify-between items-center mt-4">
            <h1 class="shrink text-lg sm:text-xl lg:text-2xl  font-bold mt-4 select-none">Products in
                {{ str_replace('Team', 'Store', $team->name) }}

            </h1>
            @can('create-product', $team)
                <x-anchor-button class="min-w-26 shrink-0" href="{{ route('products.create') }}">
                    Add Product</x-anchor-button>
            @endcan

        </div>
        <div>

            <livewire:product-list />
        </div>
    </div>
</x-app-layout>
