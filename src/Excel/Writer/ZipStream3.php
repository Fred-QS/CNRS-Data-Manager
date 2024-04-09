<?php

namespace CnrsDataManager\Excel\Writer;

use CnrsDataManager\Libs\ZipStream\Option\Archive;
use CnrsDataManager\Libs\ZipStream\ZipStream;

class ZipStream3
{
    /**
     * @param resource $fileHandle
     */
    public static function newZipStream($fileHandle): ZipStream
    {
        return new ZipStream(
            enableZip64: false,
            outputStream: $fileHandle,
            sendHttpHeaders: false,
            defaultEnableZeroHeader: false,
        );
    }
}
