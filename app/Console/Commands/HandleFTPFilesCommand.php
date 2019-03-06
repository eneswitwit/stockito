<?php

namespace App\Console\Commands;

use App\Managers\FTPFilesManager;
use App\Models\FTPFile;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * Class HandleFTPFilesCommand
 * @package App\Console\Commands
 */
class HandleFTPFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handle:ftp-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle ftp files that wsa been uploaded recently';

    /**
     * @var FTPFilesManager
     */
    protected $ftpFilesManager;

    /**
     * HandleFTPFilesCommand constructor.
     *
     * @param FTPFilesManager $ftpFilesManager
     */
    public function __construct(FTPFilesManager $ftpFilesManager)
    {
        $this->ftpFilesManager = $ftpFilesManager;
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        /**
         * @var FTPFile[]|Collection $ftpFiles
         */
        $ftpFiles = (new FTPFile())->where('handled', false)->where('processing', false)->get();

        foreach ($ftpFiles as $key => $ftpFile) {

            if (!$ftpFile->processing) {
                \DB::beginTransaction();

                try {
                    $ftpFile->processing = true;
                    $ftpFile->save();
                    $this->ftpFilesManager->handleFTPFile($ftpFile);
                } catch (\Exception $exception) {
                    $ftpFile->processing = false;
                    $ftpFile->handled = false;
                    $ftpFile->save();
                    \DB::rollBack();
                }
                \DB::commit();
            }
        }
    }
}
