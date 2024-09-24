<x-app-layout>
    <x-slot:label>
        products
    </x-slot>
    <div class="p-8">
        <p class="text-sm text-gray-600 font-semibold">Products &gt; All</p>

        <x-session></x-session>
        <div class="flex justify-between mt-4">
            <h1 class="text-2xl font-bold mt-4">Products in {{ $team->name }}

            </h1>

            <x-anchor-button href="{{ route('products.create') }}">
                Add Product</x-anchor-button>
        </div>
        <livewire:product-list />
    </div>
</x-app-layout>
