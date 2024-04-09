<?php

namespace CnrsDataManager\Excel\Writer;

use CnrsDataManager\Libs\ZipStream\Option\Archive;
use CnrsDataManager\Libs\ZipStream\ZipStream;

class ZipStream2
{
    /**
     * @param resource $fileHandle
     */
    public static function newZipStream($fileHandle): ZipStream
    {
        $options = new Archive();
        $options->setEnableZip64(false);
        $options->setOutputStream($fileHandle);

        return new ZipStream(null, $options);
    }
}
