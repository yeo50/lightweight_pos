<?php

use Livewire\Volt\Component;
use Carbon\Carbon;
use App\Models\Transaction;

new class extends Component {
    public $months;
    public $amounts;
    public $futureMonths;

    public function mount()
    {
        $month = Carbon::now()->month;
        $months = [];
        $i = $month;
        $futureMonths = [];

        for ($u = $month + 1; count($futureMonths) < 3; $u++) {
            if ($u <= 12) {
                $futureMonths[] = $u;
            }
            if ($u > 12) {
                $u = 1;
                $futureMonths[] = $u;
            }
        }
        $this->futureMonths = $futureMonths;
        while (count($months) < 5) {
            $months[] = $i;
            $i--;
            if ($i < 1) {
                $i = 12;
            }
        }
        $teamId = Auth::user()->currentTeam->id;
        $transactions = Transaction::where('team_id', $teamId)->selectRaw('MONTH(created_at) as month, SUM(amount) as total')->groupBy('month')->pluck('total', 'month');
        $amounts = [];

        foreach ($months as $key => $value) {
            $amounts[] = $transactions->get($value, 0); // Use 0 if no transactions found for that month
        }
        $monthName = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];
        $alterMonths = array_merge(array_reverse($months), $futureMonths);
        $this->months = array_map(function ($value) use ($monthName) {
            return $monthName[$value];
        }, $alterMonths);
        $this->amounts = array_reverse($amounts);
    }
}; ?>

<div>

    <div>
        <canvas id="myChart"></canvas>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const months = @json($months);
            const amounts = @json($amounts);
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Sale Volume in $',
                        data: amounts,
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                // This more specific font property overrides the global property
                                font: {
                                    size: 16
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush

</div>
