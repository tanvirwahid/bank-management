<?php

namespace App\FeeCalculators;

use App\Contracts\FeeCalculatorInterface;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class IndividualAccountFeeCalculator implements FeeCalculatorInterface
{
    public function getFee(User $user, float $amount): float
    {
        if($this->isFriday())
        {
            return 0;
        }

        $currentDate = Carbon::now();

        $firstDayOfMonth = $currentDate->startOfMonth()->format('Y-m-d H:i:s');
        $lastDayOfMonth = $currentDate->endOfMonth()->format('Y-m-d H:i:s');

        $transactionThisMonth = Transaction::withdrawal()->where('user_id', $user->id)
            ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
            ->sum('amount');
        
        $remainingFreeThisMonth = 5000 - $transactionThisMonth;
        
        if($remainingFreeThisMonth < 0)
        {
            $remainingFreeThisMonth = 0;
        }

        if($remainingFreeThisMonth >= $amount)
        {
            return 0;
        }

        $amount -= $remainingFreeThisMonth;

        if($amount <= 1000)
        {
            return 0;
        }

        return (0.015/100) * $amount;
    }

    private function isFriday()
    {
        return Carbon::now()->dayOfWeek == Carbon::FRIDAY;
    }
}
