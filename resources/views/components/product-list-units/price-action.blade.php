  <div x-data="{
      price: false,
      subLabel: false,
      priceAction: @entangle('price_action').live,
      change: '',
      method: '',
  }">
      <div x-on:click="price = !price; if(!price) subLabel = false ; change = ''" class="cursor-pointer ">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
              class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
          </svg>
      </div>
      <div x-show="price" class="absolute right-0 top-16  h-fit min-w-40  z-10 bg-gray-800 rounded-sm text-white ">
          <p @click="subLabel = true ; change='increase'; changeIncrease = true ; changeDecrease = false"
              class="px-3 py-2 select-none cursor-pointer hover:bg-gray-600">
              <span x-show="change === 'increase'" class="pe-2">&lt;</span>Increase Price
          </p>
          <p @click="subLabel = true; change='decrease'; changeDecrease = true; changeIncrease = false"
              class="px-3 py-2 select-none cursor-pointer hover:bg-gray-600">
              <span x-show="change === 'decrease'" class="pe-2">&lt;</span>Decrease Price
          </p>
      </div>
      <div x-show="subLabel"
          class="absolute right-40 top-16 w-40 h-fit rounded-lg shadow-xl bg-gray-900 z-20 text-white">
          <ul>
              <li @click="price = false; subLabel = false; priceAction = true;  method='percentage' "
                  class="px-3 py-2 hover:bg-gray-600 rounded-lg">By
                  Percentage</li>
              <li @click="price = false; subLabel = false; priceAction = true; method='value' "
                  class="px-3 py-2 hover:bg-gray-600 rounded-lg">By
                  Value</li>
          </ul>
      </div>
      <div x-show="priceAction" class="fixed inset-0 bg-gray-600/50 flex items-center justify-center">
          <div @click.outside="priceAction = false" class="min-w-80 h-40 bg-gray-900 p-4 text-white rounded-md">
              <form wire:submit="priceModified(change,method)"
                  class="w-full flex items-center justify-center flex-col space-y-3">
                  <h1> <span x-text="change" class="capitalize"></span> Price By <span x-text="method"
                          class="capitalize"></span></h1>
                  <div>
                      <input type="number" wire:model="changeValue"
                          class="bg-gray-200 mt-2 inline-block w-40 h-10 placeholder:text-xs placeholder:text-blue-600 text-black"
                          :placeholder="method === 'percentage' ? 'Enter Percentage' : 'Enter Value'">
                      <button type="submit" class="bg-violet-500 h-10 rounded-md shadow-sm shadow-white px-3 py-2">
                          Enter</button>
                  </div>
              </form>
          </div>
      </div>

  </div>
