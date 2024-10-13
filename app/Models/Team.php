<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public $today;
    public $startOfWeek;
    public $endOfWeek;
    public $thisMonth;
    public $thisYear;
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function __construct()
    {
        $this->today = Carbon::now()->today();
        $this->startOfWeek = Carbon::now()->startOfWeek();
        $this->endOfWeek = Carbon::now()->endOfWeek();
        $this->thisMonth = Carbon::now()->month;
        $this->thisYear = Carbon::now()->year;
    }
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function totalTransac()
    {
        return  Transaction::count();
    }

    public function totalTransaction($dateFormat)
    {
        if ($dateFormat == 'Today') {
            return Transaction::whereDate('created_at', $this->today)->count();
        }
        if ($dateFormat == 'This Week') {
            return Transaction::whereBetween('created_at', [$this->startOfWeek, $this->endOfWeek])->count();
        }
        if ($dateFormat == 'This Month') {
            return Transaction::whereMonth('created_at', $this->thisMonth)->count();
        }
        if ($dateFormat == 'This Year') {
            return Transaction::whereYear('created_at', $this->thisYear)->count();
        }
    }
    public function totalSaleAmount($dateFormat)
    {
        if ($dateFormat == 'Today') {
            return Transaction::whereDate('created_at', $this->today)->sum('amount');
        }

        if ($dateFormat == 'This Week') {
            return Transaction::whereBetween('created_at', [$this->startOfWeek, $this->endOfWeek])->sum('amount');
        }
        if ($dateFormat == 'This Month') {
            return Transaction::whereMonth('created_at', $this->thisMonth)->sum('amount');
        }
        if ($dateFormat == 'This Year') {
            return Transaction::whereYear('created_at', $this->thisYear)->sum('amount');
        }
    }
    public function mostSoldItem($dateFormat)
    {
        if ($dateFormat == 'Today') {
            return Sale::select('name', DB::raw('sum(count) as total_count'))
                ->whereDate('created_at', $this->today)
                ->groupBy('name')
                ->orderBy('total_count', 'DESC')
                ->limit(1)
                ->first();
        }
        if ($dateFormat == 'This Week') {
            return Sale::select('name', DB::raw('sum(count) as total_count'))
                ->whereBetween('created_at', [$this->startOfWeek, $this->endOfWeek])
                ->groupBy('name')
                ->orderBy('total_count', 'DESC')
                ->limit(1)
                ->first();
        }
        if ($dateFormat == 'This Month') {
            return Sale::select('name', DB::raw('sum(count) as total_count'))
                ->whereMonth('created_at', $this->thisMonth)
                ->groupBy('name')
                ->orderBy('total_count', 'DESC')
                ->limit(1)
                ->first();
        }
        if ($dateFormat == 'This Year') {
            return Sale::select('name', DB::raw('sum(count) as total_count'))
                ->whereYear('created_at', $this->thisYear)
                ->groupBy('name')
                ->orderBy('total_count', 'DESC')
                ->limit(1)
                ->first();
        }
    }
}
