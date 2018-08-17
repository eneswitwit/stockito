<?php

namespace App\Console\Commands;

use App\Managers\FTPFilesManager;
use App\Managers\MediaManager;
use App\Models\FTPFile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

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
     * @param FTPFilesManager $ftpFilesManager
     */
    public function __construct(FTPFilesManager $ftpFilesManager)
    {
        $this->ftpFilesManager = $ftpFilesManager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * @var FTPFile[]|Collection $ftpFiles
         */
        $ftpFiles = (new FTPFile())->where('handled', false)->get();

        $count = $ftpFiles->count();

        $this->info($count.' files for handling');

        foreach ($ftpFiles as $key => $ftpFile) {
            $this->info('Handling '.++$key.' of '.$count.' ('.$ftpFile->file.')');
            $this->ftpFilesManager->handleFTPFile($ftpFile);
        }
    }
}
