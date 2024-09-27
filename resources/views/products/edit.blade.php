<x-app-layout>
    <x-slot:label>
        products
    </x-slot:label>
    <div class="p-8">
        <p class="text-sm text-gray-600 font-semibold">Products &gt; Edit</p>
        <div class="flex justify-between px-4 py-3">
            <h1 class="text-2xl mt-4 font-semibold">Edit Product to <span
                    class="text-3xl font-bold">{{ $product->team->name }}</span>
            </h1>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" x-data="{ open: false }"
                @submit.prevent="if (open) $el.submit()">
                @csrf
                @method('DELETE')
                <button type="button" value="Delete" @click="open=true"
                    class="px-3 py-2 bg-red-600 text-white font-semibold
                rounded-lg hover:bg-red-500 focus:bg-red-500 active:bg-red-700 shadow-md tracking-wide">
                    Delete</button>
                <div class="fixed inset-0 flex items-center justify-center bg-gray-800/75" x-show="open">
                    <div class="flex flex-col justify-center items-center text-white px-4 py-2 w-[50%] h-[200px]">
                        <h1> Are you sure </h1>
                        <div class="flex justify-between space-x-4 mt-4">
                            <button @click="open= false"
                                class="px-3 py-2 border border-black min-w-32 hover:ring hover:ring-gray-300">Cancel</button>
                            <button type="submit"
                                class="bg-red-500 px-3 py-2 min-w-32 hover:ring hover:ring-red-400 ">Yes,
                                Delete</button>
                        </div>
                    </div>
                </div>
            </form>



        </div>
        <div class="mt-8">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div>
                    <input type="hidden" name="team_id" value="{{ $product->team->id }}">
                </div>
                <div class="grid grid-cols-2">
                    <div class="mt-3 pace-y-2 px-4 py-2">
                        <label for="name" class="font-semibold ps-1 py-1">Name</label> <br>
                        <div>
                            <input type="text" name="name" id="name" value="{{ $product->name }}"
                                class="rounded-xl block w-4/5 bg-gray-200">
                            <x-input-error for="name"></x-input-error>
                        </div>
                    </div>
                    <div class="mt-3 pace-y-2 px-4 py-2">
                        <label for="quantity" class="font-semibold ps-1 py-1">Quantity</label> <br>
                        <div>
                            <input type="number" name="quantity" id="quantity" value="{{ $product->quantity }}"
                                class="rounded-xl block w-4/5 bg-gray-200">
                        </div>
                    </div>
                    <div class="mt-3 pace-y-2 px-4 py-2">
                        <label for="price" class="font-semibold ps-1 py-1">Price</label> <br>
                        <div>
                            <input type="number" name="price" id="price" value="{{ $product->price }}"
                                class="rounded-xl block w-4/5 bg-gray-200">
                        </div>
                    </div>
                    <div class="mt-3 space-y-2 px-4 py-2">
                        <label for="barcode" class="font-semibold ps-1 py-1">Barcode</label> <br>
                        <div>
                            <input type="text" name="barcode" id="barcode" value="{{ $product->barcode }}"
                                class="rounded-xl block w-4/5 bg-gray-200">
                        </div>
                    </div>
                </div>
                <div class="mt-4 ps-4 space-x-3">
                    <x-button
                        class="hover:ring focus:ring active:ring inline-flex min-w-20 items-center justify-center">Update</x-button>
                    <a href="{{ route('products.index') }}"
                        class="border px-3 py-[7px]  inline-flex items-center justify-center rounded-md min-w-20 border-gray-500  hover:ring focus:ring active:ring   hover:bg-transparent hover:border-black
                            focus:bg-transparent active:bg-transparent">
                        Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
{{-- <script>
    function confirmDelete() {
        return confirm("are you sure you want to delete this product")
    }
</script> --}}
