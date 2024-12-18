<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'team_id',
        'name',
        'quantity',
        'price',
        'barcode',
        'count',
        'photo'
    ];
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
