<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentHistory extends Model
{
    use HasFactory;

    public $table = 'investments_history';

    protected $fillable = [
        'account_id',
        'crypto_id',
        'amount',
        'bought_at',
        'status',
    ];
}
