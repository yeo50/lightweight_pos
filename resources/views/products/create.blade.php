<x-app-layout>
    <x-slot:label>
        products
    </x-slot:label>
    <div class="p-8">
        <p class="text-sm text-gray-600 font-semibold">Products &gt; Add</p>
        <h1 class="text-2xl mt-4 font-semibold">Add Product to <span class="text-3xl font-bold">{{ $team->name }}</span>
        </h1>
        <div class="mt-8">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                </div>
                <div class="grid grid-cols-2 gap-4 ">
                    <div class="mt-3 space-y-2 px-4 py-2 ">
                        <label for="name" class="font-semibold ps-1 py-1">Name</label> <br>
                        <div>
                            <input type="text" name="name" id="name"
                                class="rounded-xl block w-4/5  bg-gray-200">
                        </div>
                    </div>

                    <div class="mt-3  space-y-2 px-4 py-2">
                        <label for="price" class="font-semibold ps-1 py-1">Price</label> <br>
                        <div>
                            <input type="number" name="price" id="price"
                                class="rounded-xl block w-4/5 bg-gray-200">
                        </div>
                    </div>
                    <div class="mt-3 space-y-2 px-4 py-2">
                        <label for="barcode" class="font-semibold ps-1 py-1">Barcode</label> <br>
                        <div>
                            <input type="text" name="barcode" id="barcode"
                                class="rounded-xl block w-4/5 bg-gray-200">
                        </div>
                    </div>
                    <div class="mt-3 space-y-2 px-4 py-2">
                        <label for="photo" class="font-semibold ps-1 py-1">Upload Product's Photo</label>
                        <input type="file" name="photo" id="photo"
                            class="rounded-xl ps-3 py-2 block w-4/5 bg-gray-200">
                    </div>

                </div>
                <div class="mt-4 space-x-3 ps-4">
                    <x-button class="hover:ring focus:ring active:ring min-w-20 inline-flex justify-center">Add
                    </x-button> <a href="{{ route('products.index') }}"
                        class="border px-3 py-[7px]  rounded-md inline-flex items-center justify-center min-w-20 border-gray-500  hover:ring focus:ring active:ring   hover:bg-transparent hover:border-black
                            focus:bg-transparent active:bg-transparent">
                        Cancel</a>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
