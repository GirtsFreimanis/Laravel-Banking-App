<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments';
    protected $fillable = [
        'account_id',
        'crypto_id',
        'amount',
    ];
}
