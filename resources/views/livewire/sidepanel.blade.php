<?php

use Livewire\Volt\Component;
new class extends Component {
    public $active = '';
}; ?>


<div class="px-6">
    <ul>
        <a href="{{ route('dashboard') }}">
            <li class="px-3 py-2 font-semibold cursor-pointer {{ $active == 'dashboard' ? 'active' : '' }}">Dashboard
            </li>
        </a>
        <a wire:navigate href="/sales">
            <li class="px-3 py-2 font-semibold cursor-pointer {{ $active == 'sales' ? 'active' : '' }}">Sales Screen
            </li>
        </a>
        <a wire:navigate href="/products">
            <li class="px-3 py-2 font-semibold cursor-pointer {{ $active == 'products' ? 'active' : '' }}"> Products
            </li>
        </a>
        <a wire:navigate href="/instocks">
            <li class="px-3 py-2 font-semibold cursor-pointer {{ $active == 'instocks' ? 'active' : '' }}">Add Stock
            </li>
        </a>


    </ul>
</div>
