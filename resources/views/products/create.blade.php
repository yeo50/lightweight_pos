<x-app-layout>
    <x-slot:label>
        products
    </x-slot:label>
    <div class="p-8">
        <p class="text-sm text-gray-600 font-semibold">Products &gt; Add</p>
        <h1 class="text-2xl mt-4 font-semibold">Add Product to <span class="text-3xl font-bold">{{ $team->name }}</span>
        </h1>
        <div class="mt-8">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div>
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                </div>
                <div class="mt-3">
                    <label for="name" class="font-semibold ps-1 py-1">Name</label> <br>
                    <div class="border-2 inline-block rounded-[14px]">
                        <input type="text" name="name" id="name" class="rounded-xl bg-gray-200">
                    </div>
                </div>
                <div class="mt-3">
                    <label for="quantity" class="font-semibold ps-1 py-1">Quantity</label> <br>
                    <div class="border-2 inline-block rounded-[14px]">
                        <input type="number" name="quantity" id="quantity" class="rounded-xl bg-gray-200">
                    </div>
                </div>
                <div class="mt-3">
                    <label for="price" class="font-semibold ps-1 py-1">Price</label> <br>
                    <div class="border-2 inline-block rounded-[14px]">
                        <input type="number" name="price" id="price" class="rounded-xl bg-gray-200">
                    </div>
                </div>
                <div class="mt-4">
                    <x-button>Add </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
