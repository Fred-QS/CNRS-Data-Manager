<?php

namespace CnrsDataManager\Excel\Calculation\Statistical;

use CnrsDataManager\Excel\Calculation\Exception;
use CnrsDataManager\Excel\Calculation\Information\ExcelError;

class StatisticalValidations
{
    public static function validateFloat(mixed $value): float
    {
        if (!is_numeric($value)) {
            throw new Exception(ExcelError::VALUE());
        }

        return (float) $value;
    }

    public static function validateInt(mixed $value): int
    {
        if (!is_numeric($value)) {
            throw new Exception(ExcelError::VALUE());
        }

        return (int) floor((float) $value);
    }

    public static function validateBool(mixed $value): bool
    {
        if (!is_bool($value) && !is_numeric($value)) {
            throw new Exception(ExcelError::VALUE());
        }

        return (bool) $value;
    }
}
