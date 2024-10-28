<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Carbon\Carbon;
use App\Models\Sale;

new class extends Component {
    public $totalSaleAmount;
    public $totalTransaction;
    public $mostSoldItem;
    public $today;
    public $thisWeek;
    public $thisMonth;
    public $thisYear;
    public $mostSoldItemCount;
    public function mount()
    {
        $this->today = Carbon::now()->today();
        $this->thisMonth = Carbon::now()->month;
        $this->totalSaleAmount = Auth::user()->currentTeam->totalSaleAmount('This Month');
        $this->totalTransaction = Auth::user()->currentTeam->totalTransaction('This Month');
        $mostSoldItem = Auth::user()->currentTeam->mostSoldItem('This Month');
        $this->mostSoldItem = $mostSoldItem ? $mostSoldItem->name : 'No Item';
        $this->mostSoldItemCount = $mostSoldItem ? $mostSoldItem->total_count : '00';
    }

    public function statistics($value)
    {
        if ($value[0] == 'Total Sale Amount') {
            $this->totalSaleAmount = Auth::user()->currentTeam->totalSaleAmount($value[1]);
        }
        if ($value[0] == 'Total Transaction') {
            $this->totalTransaction = Auth::user()->currentTeam->totalTransaction($value[1]);
        }
        if ($value[0] == 'Most Sold Item') {
            $mostSoldItem = Auth::user()->currentTeam->mostSoldItem($value[1]);
            $this->mostSoldItem = $mostSoldItem ? $mostSoldItem->name : 'No Item';
            $this->mostSoldItemCount = $mostSoldItem ? $mostSoldItem->total_count : '00';
        }
    }
    public function testTeam()
    {
        dd(Auth::user()->currentTeam);
    }
}; ?>
<section class="flex space-x-3">

    <x-infos mainLabel="Total Sale Amount" amount="{{ $totalSaleAmount }}"></x-infos>
    <x-infos mainLabel="Total Transaction" amount="{{ $totalTransaction }}"></x-infos>
    <x-infos mainLabel="Most Sold Item" amount="{{ $mostSoldItemCount }}" mostSoldItem="{{ $mostSoldItem }}"></x-infos>

</section>
{{-- <div>
    <button wire:click="testTeam">click test</button>
</div> --}}
