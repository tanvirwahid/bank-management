<?php

namespace App\Services;

use App\Contracts\FeeCalculatorInterface;
use App\Contracts\TransactionHandlerInterface;
use App\Exceptions\InsufficientBalanceException;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionService implements TransactionHandlerInterface
{
    public function deposit(User $user, float $amount): Transaction
    {
        return DB::transaction(function() use ($user, $amount) {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'transaction_type' => 'deposit',
                'amount' => $amount,
                'fee' => 0
            ]);

            $user->balance = $user->balance + $amount;
            $user->save();

            return $transaction;
        });
    }

    /**
     * @throws InsufficientBalanceException if not enough balance
     */
    public function withdraw(FeeCalculatorInterface $feeCalculator, User $user, float $amount): Transaction
    {
        $fee = $feeCalculator->getFee($user, $amount);
        $totalWithdrawal = $fee + $amount;

        if($totalWithdrawal > $user->balance)
        {
            throw new InsufficientBalanceException('Not enough balance');
        }
    
        return DB::transaction(function() use ($totalWithdrawal, $user, $amount, $fee) {

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'transaction_type' => 'withdrawal',
                'amount' => $amount,
                'fee' => $fee
            ]);

            $user->balance = $user->balance - $totalWithdrawal;
            $user->save();

            return $transaction;

        });
    }
}
