<?php

namespace App\Contracts;

use App\Models\Transaction;
use App\Models\User;
use App\Exceptions\InsufficientBalanceException;

interface TransactionHandlerInterface
{
    public function deposit(User $user, float $amount): Transaction;

    /**
     * @throws InsufficientBalanceException if not enough balance
     */
    public function withdraw(FeeCalculatorInterface $feeCalculator, User $user, float $amount): Transaction;
}
