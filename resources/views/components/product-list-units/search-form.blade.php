<div class="w-fit rounded-2xl ">
    <form wire:submit="search">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="h-6 inline-block text-gray-500 translate-x-10 ">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>

        <input type="text" placeholder="Search" wire:model="search_value"
            class="ps-10 inline-block max-sm:placeholder:text-sm max-sm:w-40 bg-gray-100 h-8 rounded-xl  ">
    </form>
</div>
