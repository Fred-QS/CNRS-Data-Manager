<?php

declare(strict_types=1);

namespace CnrsDataManager\Libs\ZipStream\Exception;

use CnrsDataManager\Libs\ZipStream\Exception;

/**
 * This Exception gets invoked if a counter value exceeds storage size
 */
class OverflowException extends Exception
{
    /**
     * @internal
     */
    public function __construct()
    {
        parent::__construct('File size exceeds limit of 32 bit integer. Please enable "zip64" option.');
    }
}
