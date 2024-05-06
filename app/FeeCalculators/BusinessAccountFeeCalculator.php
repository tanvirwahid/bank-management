<?php

namespace App\FeeCalculators;

use App\Contracts\FeeCalculatorInterface;
use App\Models\Transaction;
use App\Models\User;

class BusinessAccountFeeCalculator implements FeeCalculatorInterface
{
    public function getFee(User $user, float $amount): float
    {
        $totalTransaction = Transaction::withdrawal()->where('user_id', $user->id)
            ->sum('amount');
        
        if($totalTransaction >= 50000)
        {
            return (0.015/100) * $amount;
        }

        $remainingTillFiftyK = 50000 - $totalTransaction;
        
        if($amount <= $remainingTillFiftyK)
        {
            return (0.025/100) * $amount;
        }

        $oldFee = $remainingTillFiftyK * (0.025/100);
        $newFee = ($amount - $remainingTillFiftyK) * (0.015/100);

        return $oldFee + $newFee;
    }
}
