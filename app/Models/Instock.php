<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instock extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'team_id',
        'product_id',
        'quantity'
    ];
}
