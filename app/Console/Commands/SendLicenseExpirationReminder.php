<?php

namespace App\Console\Commands;

use App\Mail\ReminderExpirationLicense;
use App\Models\UsageLicense;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Log;

class SendLicenseExpirationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:license-expiration-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Emails to Brands, when some of their licenses are expiring in 14 days  or 1 day.';

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

        $usageLicenses = UsageLicense::where('expired_at', Carbon::now()->startOfDay()->addDays(14)->toDateTimeString())->with([
            'license',
            'license.media',
            'license.media.brand',
            'license.media.brand.user'
        ])->get();

        foreach ($usageLicenses as $usageLicense) {

            $mainLicense = $usageLicense->license;
            $media = $mainLicense->media;
            $brand = $media->brand;
            $email = $brand->user->email;

            Mail::to($email)->send(new ReminderExpirationLicense(
                $mainLicense->getLicenseTitle(),
                $media->title,
                $usageLicense->invoice_number,
                14));
        }

        $usageLicensesOne = UsageLicense::where('expired_at', Carbon::now()->addDays(1)->toDateTimeString())->with([
            'license',
            'license.media',
            'license.media.brand',
            'license.media.brand.user'
        ])->get();

        foreach ($usageLicensesOne as $usageLicense) {

            $mainLicense = $usageLicense->license;
            $media = $mainLicense->media;
            $brand = $media->brand;
            $email = $brand->user->email;

            Mail::to($email)->send(new ReminderExpirationLicense(
                $mainLicense->getLicenseTitle(),
                $media->title,
                $usageLicense->invoice_number,
                1));
        }

    }
}
