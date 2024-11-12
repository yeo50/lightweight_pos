 @props(['filter', 'filterPriceRange', 'search_value', 'min', 'max'])

 @if (!$filter)
     <div x-show="rangeFilter" class="flex justify-between px-4">
         <div class="py-2 px-3 border-t max-sm:text-sm">
             <span class="max-sm:block max-sm:text-sm">Active Filter:</span> <span class="capitalize max-sm:text-sm"
                 x-text="rangeFilterValue"></span>
             @if ($filterPriceRange)
                 Between
                 <span>{{ $min }} $ and {{ $max }}$</span>
             @endif
             @if (!$filterPriceRange)
                 <label for="min">min</label> <input type="number" id="min" wire:model='min'
                     class="bg-gray-300 inline-block w-16 h-6">
                 <label for="max">max</label> <input type="number" id="max" wire:model="max"
                     class="bg-gray-300 inline-block w-16 h-6">
                 <x-button x-on:click="$wire.filterRange(rangeFilterValue)">Filter</x-button>
             @endif
         </div>
         <div>
             <button x-on:click="rangeFilter = false ; $wire.rangeFilterCancel(rangeFilterValue); $wire.$refresh()">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="h-8 w-8">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                 </svg> </button>
         </div>
     </div>
 @endif

 @if ($filter)
     <div class="flex justify-between px-4">
         <div class="py-2 px-3 border-t">
             Active Filter: <span class="font-semibold">{{ $search_value }}</span>
         </div>
         <div>
             <button x-on:click="$wire.searchCancel; rangeFilter = false"> <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                 </svg></button>
         </div>
     </div>
 @endif
