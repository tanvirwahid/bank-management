<?php

namespace App\Contracts;

use App\Models\User;

interface FeeCalculatorFactory
{
    public function getFeeCalculator(string $accountType): FeeCalculatorInterface;
}
