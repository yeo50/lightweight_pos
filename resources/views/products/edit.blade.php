<x-app-layout>
    <x-slot:label>
        products
    </x-slot:label>
    <div class="p-8">
        <p class="text-sm text-gray-600 font-semibold">Products &gt; Edit</p>
        <h1 class="text-2xl mt-4 font-semibold">Edit Product to <span
                class="text-3xl font-bold">{{ $product->team->name }}</span>
        </h1>
        <div class="mt-8">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div>
                    <input type="hidden" name="team_id" value="{{ $product->team->id }}">
                </div>
                <div class="mt-3">
                    <label for="name" class="font-semibold ps-1 py-1">Name</label> <br>
                    <div class="border-2 inline-block rounded-[14px]">
                        <input type="text" name="name" id="name" value="{{ $product->name }}"
                            class="rounded-xl bg-gray-200">
                        <x-input-error for="name"></x-input-error>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="quantity" class="font-semibold ps-1 py-1">Quantity</label> <br>
                    <div class="border-2 inline-block rounded-[14px]">
                        <input type="number" name="quantity" id="quantity" value="{{ $product->quantity }}"
                            class="rounded-xl bg-gray-200">
                    </div>
                </div>
                <div class="mt-3">
                    <label for="price" class="font-semibold ps-1 py-1">Price</label> <br>
                    <div class="border-2 inline-block rounded-[14px]">
                        <input type="number" name="price" id="price" value="{{ $product->price }}"
                            class="rounded-xl bg-gray-200">
                    </div>
                </div>
                <div class="mt-4 space-x-3">
                    <x-button class="hover:ring focus:ring active:ring">Update</x-button> <a
                        href="{{ route('products.index') }}"><x-button type="button"
                            class="border border-gray-500 hover:ring focus:ring active:ring  bg-transparent text-black hover:bg-transparent hover:border-black focus:bg-transparent active:bg-transparent">Cancel</x-button></a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
