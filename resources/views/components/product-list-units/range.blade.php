 <div x-data="{ range: false, }" @click="range=!range ; if(price) price = false, subLabel = false;"
     @click.outside="range = false" class="cursor-pointer relative">
     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
         class="size-6">
         <path stroke-linecap="round" stroke-linejoin="round"
             d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
     </svg>
     <div x-show="range" class="absolute top-10 right-0 w-40 h-fit bg-gray-800 text-white">
         <ul>
             <li @click="filter= false; rangeFilter = true;  rangeFilterValue = 'price'"
                 class="px-3 py-2 hover:bg-gray-500 select-none">Price Range
             </li>
             <li @click="filter= false; rangeFilter = true;  rangeFilterValue = 'quantity'"
                 class="px-3 py-2 hover:bg-gray-500 select-none">Quantity
                 Range</li>
         </ul>
     </div>
 </div>
