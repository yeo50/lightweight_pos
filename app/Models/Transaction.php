<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'team_id',
        'transaction_no',
        'amount',
        'payment_method',
    ];
    public function totalTransac()
    {
        return  'hello';
    }
}
