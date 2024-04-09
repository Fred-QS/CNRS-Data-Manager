<?php

namespace CnrsDataManager\Excel;

interface IComparable
{
    /**
     * Get hash code.
     *
     * @return string Hash code
     */
    public function getHashCode(): string;
}
