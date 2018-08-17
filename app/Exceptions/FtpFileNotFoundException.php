<?php

namespace App\Exceptions;

use App\Models\FTPFile;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class FtpFileNotFoundException extends Exception
{
    /**
     * @var FTPFile
     */
    protected $ftpFile;

    /**
     * FtpFileNotFoundException constructor.
     * @param string $message
     * @param FTPFile $FTPFile
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', FTPFile $FTPFile, int $code = 0, Throwable $previous = null)
    {
        $this->ftpFile = $FTPFile;
        parent::__construct($message, $code, $previous);
    }

//    /**
//     * Report the exception.
//     *
//     * @return void
//     */
//    public function report(): void
//    {
//        Log::error($this->getMessage(), ['ftpFile' => $this->ftpFile->toArray()]);
//    }
}
