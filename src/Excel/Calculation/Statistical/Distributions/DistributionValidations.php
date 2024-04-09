<?php

namespace CnrsDataManager\Excel\Calculation\Statistical\Distributions;

use CnrsDataManager\Excel\Calculation\Exception;
use CnrsDataManager\Excel\Calculation\Information\ExcelError;
use CnrsDataManager\Excel\Calculation\Statistical\StatisticalValidations;

class DistributionValidations extends StatisticalValidations
{
    public static function validateProbability(mixed $probability): float
    {
        $probability = self::validateFloat($probability);

        if ($probability < 0.0 || $probability > 1.0) {
            throw new Exception(ExcelError::NAN());
        }

        return $probability;
    }
}
