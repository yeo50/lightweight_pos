      <div x-data="{ open: false, timePeriod: ['today', 'Week', 'Month', 'Year'], currentPeriod: 'This Month', }" class="flex-1 md:min-h-36 border shadow-lg rounded-lg px-4 py-3">

          <div class="flex justify-between ">
              <div class="flex-1">
                  <p class="capitalize text-sm">{{ Auth::user()->currentTeam->name }}</p>
                  <h1 class="py-2 font-semibold tracking-wider text-xl">{{ $mainLabel ?? '' }} </h1>
                  <h1 class="py-2  font-bold tracking-widest text-blue-600 text-lg">
                      @if ($mainLabel == 'Total Sale Amount')
                          <span>{{ $amount ?? '00' }} $</span>
                      @elseif ($mainLabel == 'Total Transaction')
                          <span>{{ $amount ?? '00' }} times </span>
                      @else
                          @if ($mostSoldItem)
                              <span class="capitalize"> {{ $mostSoldItem ?? 'No Item' }} , {{ $amount ?? '00' }}
                                  times</span>
                          @endif
                      @endif

                  </h1>
              </div>
              <div>
                  <button @click="open = !open;" x-text="currentPeriod"
                      class="px-3 py-2 text-xs border rounded-md shadow-md">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="h-3 w-3 inline-block">
                          <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                      </svg>
                  </button>
                  <ul x-show="open" class="text-center">
                      <template x-for="time in timePeriod" :key="time">
                          <li class="border border-t-0 text-xs cursor-pointer py-1">
                              <span
                                  @click =" open = false ;currentPeriod = time == 'today' ? 'Today' : time == 'Week' ? 'This Week': time == 'Month' ? 'This Month' : 'This Year'; $wire.statistics([ '{{ $mainLabel }}',currentPeriod]) "
                                  x-text="time == 'today' ? 'Today' : 'This ' + time"></span>
                          </li>
                      </template>
                  </ul>
              </div>
          </div>

      </div>
