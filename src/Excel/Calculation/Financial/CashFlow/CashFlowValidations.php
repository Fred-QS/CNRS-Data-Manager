<?php

namespace CnrsDataManager\Excel\Calculation\Financial\CashFlow;

use CnrsDataManager\Excel\Calculation\Exception;
use CnrsDataManager\Excel\Calculation\Financial\Constants as FinancialConstants;
use CnrsDataManager\Excel\Calculation\Financial\FinancialValidations;
use CnrsDataManager\Excel\Calculation\Information\ExcelError;

class CashFlowValidations extends FinancialValidations
{
    public static function validateRate(mixed $rate): float
    {
        $rate = self::validateFloat($rate);

        return $rate;
    }

    public static function validatePeriodType(mixed $type): int
    {
        $rate = self::validateInt($type);
        if (
            $type !== FinancialConstants::PAYMENT_END_OF_PERIOD
            && $type !== FinancialConstants::PAYMENT_BEGINNING_OF_PERIOD
        ) {
            throw new Exception(ExcelError::NAN());
        }

        return $rate;
    }

    public static function validatePresentValue(mixed $presentValue): float
    {
        return self::validateFloat($presentValue);
    }

    public static function validateFutureValue(mixed $futureValue): float
    {
        return self::validateFloat($futureValue);
    }
}
