<?php

namespace CnrsDataManager\Excel\Calculation\DateTimeExcel;

use DateTimeInterface;
use CnrsDataManager\Excel\Calculation\ArrayEnabled;
use CnrsDataManager\Excel\Calculation\Exception;
use CnrsDataManager\Excel\Calculation\Information\ExcelError;
use CnrsDataManager\Excel\Shared\Date as SharedDateHelper;

class Days
{
    use ArrayEnabled;

    /**
     * DAYS.
     *
     * Returns the number of days between two dates
     *
     * Excel Function:
     *        DAYS(endDate, startDate)
     *
     * @param array|DateTimeInterface|float|int|string $endDate Excel date serial value (float),
     *           PHP date timestamp (integer), PHP DateTime object, or a standard date string
     *                         Or can be an array of date values
     * @param array|DateTimeInterface|float|int|string $startDate Excel date serial value (float),
     *           PHP date timestamp (integer), PHP DateTime object, or a standard date string
     *                         Or can be an array of date values
     *
     * @return array|int|string Number of days between start date and end date or an error
     *         If an array of values is passed for the $startDate or $endDays,arguments, then the returned result
     *            will also be an array with matching dimensions
     */
    public static function between(array|DateTimeInterface|float|int|string $endDate, array|DateTimeInterface|float|int|string $startDate): array|int|string
    {
        if (is_array($endDate) || is_array($startDate)) {
            return self::evaluateArrayArguments([self::class, __FUNCTION__], $endDate, $startDate);
        }

        try {
            $startDate = Helpers::getDateValue($startDate);
            $endDate = Helpers::getDateValue($endDate);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        // Execute function
        $PHPStartDateObject = SharedDateHelper::excelToDateTimeObject($startDate);
        $PHPEndDateObject = SharedDateHelper::excelToDateTimeObject($endDate);

        $days = ExcelError::VALUE();
        $diff = $PHPStartDateObject->diff($PHPEndDateObject);
        if ($diff !== false && !is_bool($diff->days)) {
            $days = $diff->days;
            if ($diff->invert) {
                $days = -$days;
            }
        }

        return $days;
    }
}
