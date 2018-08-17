<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Media;
use App\Services\FTPService;
use Illuminate\Console\Command;

class CheckFTPUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:ftp-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check FTP users and recreate, if it needed';

    /**
     * @var FTPService
     */
    protected $FTPService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FTPService $FTPService)
    {
        $this->FTPService = $FTPService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        /**
         * @var Brand[] $brands
         */
        $brands = Brand::all();

        foreach ($brands as $brand) {
            $this->info('Checking brand '.$brand->id);
            if (!$this->FTPService::checkExistFTPUserForBrand($brand)) {
                $this->info('Creating FTP user for brand '.$brand->id);
                $ftpUser = $this->FTPService::makeFTPUserForBrand($brand);
                $ftpUser->save();
                $brand->ftpUser()->associate($ftpUser);
                $brand->save();
                $this->info('Created FTP user with id '.$ftpUser->id);
            }
        }
    }
}
