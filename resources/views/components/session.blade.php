<div>
    @if (session('err-message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 9000)" x-show="show"
            class="absolute top-0 right-8  border border-red-600 text-red-500 text-center px-4 py-4">
            {{ session('err-message') }}
            <button @click="show = false" type="button" class="absolute top-0 right-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>

            </button>
        </div>
    @endif

    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 9000)" x-show="show"
            class="absolute top-0 right-8  border border-green-600 text-green-500 text-center px-4 py-4">
            {{ session('message') }}
            <button @click="show = false" type="button" class="absolute top-0 right-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>

            </button>
        </div>
    @endif
</div>
