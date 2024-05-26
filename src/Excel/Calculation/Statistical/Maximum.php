<?php

namespace CnrsDataManager\Excel\Calculation\Statistical;

use CnrsDataManager\Excel\Calculation\Functions;
use CnrsDataManager\Excel\Calculation\Information\ErrorValue;

class Maximum extends MaxMinBase
{
    /**
     * MAX.
     *
     * MAX returns the value of the element of the values passed that has the highest value,
     *        with negative numbers considered smaller than positive numbers.
     *
     * Excel Function:
     *        MAX(value1[,value2[, ...]])
     *
     * @param mixed ...$args Data values
     */
    public static function max(mixed ...$args): float|int|string
    {
        $returnValue = null;

        // Loop through arguments
        $aArgs = Functions::flattenArray($args);
        foreach ($aArgs as $arg) {
            if (ErrorValue::isError($arg)) {
                $returnValue = $arg;

                break;
            }
            // Is it a numeric value?
            if ((is_numeric($arg)) && (!is_string($arg))) {
                if (($returnValue === null) || ($arg > $returnValue)) {
                    $returnValue = $arg;
                }
            }
        }

        if ($returnValue === null) {
            return 0;
        }

        return $returnValue;
    }

    /**
     * MAXA.
     *
     * Returns the greatest value in a list of arguments, including numbers, text, and logical values
     *
     * Excel Function:
     *        MAXA(value1[,value2[, ...]])
     *
     * @param mixed ...$args Data values
     */
    public static function maxA(mixed ...$args): float|int|string
    {
        $returnValue = null;

        // Loop through arguments
        $aArgs = Functions::flattenArray($args);
        foreach ($aArgs as $arg) {
            if (ErrorValue::isError($arg)) {
                $returnValue = $arg;

                break;
            }
            // Is it a numeric value?
            if ((is_numeric($arg)) || (is_bool($arg)) || ((is_string($arg) && ($arg != '')))) {
                $arg = self::datatypeAdjustmentAllowStrings($arg);
                if (($returnValue === null) || ($arg > $returnValue)) {
                    $returnValue = $arg;
                }
            }
        }

        if ($returnValue === null) {
            return 0;
        }

        return $returnValue;
    }
}