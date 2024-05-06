<?php

namespace App\Contracts;

use App\Models\User;

interface FeeCalculatorInterface
{
    public function getFee(User $user, float $amount): float;
}
