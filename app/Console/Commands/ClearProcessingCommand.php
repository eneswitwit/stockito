<?php

namespace App\Console\Commands;

use App\Models\FTPFile;
use Illuminate\Console\Command;

class ClearProcessingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processing:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear processing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ftpFiles = (new FTPFile)->where('queuing', true)->get();
        $ftpFiles->each(function (FTPFile $FTPFile) {
            $FTPFile->delete();
        });
    }
}
