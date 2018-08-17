<?php

namespace App\Managers;

use App\Models\FTPFile;
use App\Models\Media;

interface FTPFilesManagerInterface
{
    /**
     * @param FTPFile $ftpFile
     * @return Media
     */
    public function getMediaForFTPFile(FTPFile $ftpFile): Media;
}
