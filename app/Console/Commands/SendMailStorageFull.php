<?php

namespace App\Console\Commands;

use App\Mail\StorageFull;
use App\Models\UsageLicense;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Brand;
use Illuminate\Support\Facades\Mail;
use App\Services\UploadService;
use Log;

class SendMailStorageFull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:storage-full-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Emails to Brands, when their storage is almost full';

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
        foreach (Brand::all() as $brand) {
            if(1.05*UploadService::calculateUsedStorage($brand)['used']  >= $brand->getProduct()->storage) {

                if($brand->storage_full_mail === 0) {
                    Mail::to($brand->user->email)->send(new StorageFull());
                    $brand->storage_full_mail = 1;
                    $brand->save();
                }
            } else {
                $brand->storage_full_mail = 0;
                $brand->save();
            }
        }
    }
}

