<?php

namespace App\FeeCalculators\Factories;

use App\Contracts\FeeCalculatorFactory as ContractsFeeCalculatorFactory;
use App\FeeCalculators\BusinessAccountFeeCalculator;
use App\FeeCalculators\IndividualAccountFeeCalculator;
use App\Contracts\FeeCalculatorInterface;

class FeeCalculatorFactory implements ContractsFeeCalculatorFactory
{
    const FEE_CALCULATORS = [
        'Individual' => IndividualAccountFeeCalculator::class,
        'Business' => BusinessAccountFeeCalculator::class
    ];

    public function getFeeCalculator(string $accountType): FeeCalculatorInterface
    {
        return app()->make(self::FEE_CALCULATORS[$accountType]);
    }
}
