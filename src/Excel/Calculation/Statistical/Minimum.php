<?php

namespace CnrsDataManager\Excel\Calculation\Statistical;

use CnrsDataManager\Excel\Calculation\Functions;
use CnrsDataManager\Excel\Calculation\Information\ErrorValue;

class Minimum extends MaxMinBase
{
    /**
     * MIN.
     *
     * MIN returns the value of the element of the values passed that has the smallest value,
     *        with negative numbers considered smaller than positive numbers.
     *
     * Excel Function:
     *        MIN(value1[,value2[, ...]])
     *
     * @param mixed ...$args Data values
     */
    public static function min(mixed ...$args): float|int|string
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
                if (($returnValue === null) || ($arg < $returnValue)) {
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
     * MINA.
     *
     * Returns the smallest value in a list of arguments, including numbers, text, and logical values
     *
     * Excel Function:
     *        MINA(value1[,value2[, ...]])
     *
     * @param mixed ...$args Data values
     */
    public static function minA(mixed ...$args): float|int|string
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
                if (($returnValue === null) || ($arg < $returnValue)) {
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