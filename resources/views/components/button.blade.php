<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex  items-center px-2 py-1 max-sm:mt-1 sm:px-4 sm:py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
