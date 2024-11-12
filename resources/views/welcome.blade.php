<x-guest-layout>
    <div class="w-full h-screen bg-violet-600">

        <nav class="w-full h-16  flex justify-end  py-2 bg-violet-700 shadow-md">
            <ul class="flex justify-between items-center gap-4 pe-3 select-none   text-xl font-semibold  text-white">
                @guest
                    <li><a href="/login">Login</a></li>
                    <li class="bg-white px-2  py-1 rounded-lg text-gray-800"><a href="/register">Get Lighter Free</a></li>
                @endguest
                @auth

                    <li>
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <a href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    </li>
                @endauth
            </ul>
        </nav>


        <div class="lg:flex mt-4 ">
            <div class="flex-1 max-lg:hidden"><img class="ml-40 " src="{{ asset('storage/photos/girl-pointing.png') }}"
                    alt="girl pointing">
            </div>
            <div class="flex-1 max-lg:text-center">
                <h1 class=" text-xl md:text-3xl xl:text-5xl  text-white font-bold tracking-wider mt-6">Looking For
                    Lightweight <br>

                    <span class="mt-2 py-3 inline-block">& Affordable POS ?</span>
                </h1>
                <h1 class="mt-6 py-2"> <span class="text-2xl md:text-4xl xl:text-6xl font-bold text-gray-800">Lighter
                        POS</span> <span class="text-sm md:text-lg xl:text-xl font-semibold tracking-wide text-white">is
                        Here </span>
                </h1>
                <ul class="text-lg md:text-xl xl:text-2xl p-3 mt-3">
                    <li class="text-white"><span class="text-blue-300">&#10003;</span> Faster checkout
                    </li>
                    <li class="text-white"><span class="text-blue-300">&#10003;</span> Easy to
                        understand usage
                    </li>
                    <li class="text-white"><span class="text-blue-300">&#10003;</span> Free to use
                    </li>
                    <li class="text-white"><span class="text-blue-300">&#10003;</span> Convenience for
                        Small Store
                    </li>
                </ul>
            </div>
        </div>
    </div>

</x-guest-layout>
