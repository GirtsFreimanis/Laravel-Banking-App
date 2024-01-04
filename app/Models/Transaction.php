<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'exchanged_amount',
        'account_from',
        'account_to',
        'currency_from',
        'currency_to',
        'account_from_balance',
        'account_to_balance',
    ];
}
