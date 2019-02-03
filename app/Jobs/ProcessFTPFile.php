<?php

namespace App\Jobs;

use App\Exceptions\FtpFileNotFoundException;
use App\Managers\FTPFilesManager;
use App\Models\FTPFile;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerAwareInterface;

class ProcessFTPFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var FTPFile
     */
    protected $FTPFile;

    /**
     * ProcessFTPFile constructor.
     * @param FTPFile $FTPFile
     */
    public function __construct(FTPFile $FTPFile)
    {
        $this->FTPFile = $FTPFile;
    }

    /**
     * @param FTPFilesManager $filesManager
     * @throws \Exception
     */
    public function handle(FTPFilesManager $filesManager): void
    {
        if (!$this->FTPFile->processing) {
            \DB::beginTransaction();
            try {
                $filesManager->handleFTPFile($this->FTPFile);
            } catch (\Exception $exception) {
                $this->FTPFile->processing = false;
                $this->FTPFile->save();
                \DB::rollBack();
            }
            \DB::commit();
        }
    }
}
