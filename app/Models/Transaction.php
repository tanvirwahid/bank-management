<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'name',
        'user_id',
        'transaction_type',
        'amount',
        'fee'
    ];

    public function scopeDeposit(Builder $query)
    {
        $query->where('transaction_type', 'deposit');
    }

    public function scopeWithdrawal(Builder $query)
    {
        $query->where('transaction_type', 'withdrawal');
    }
}
