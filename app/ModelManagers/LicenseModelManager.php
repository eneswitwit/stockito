<?php

// namespace
namespace App\ModelManagers;

// use
use App\Models\License;
use App\Models\UsageLicense;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\Request;
use Log;

/**
 * Class LicenseModelManager
 *
 * @package App\ModelManagers
 */
class LicenseModelManager
{
    protected $model = License::class;

    /**
     * @param UsageLicense $usageLicense
     * @param Request $request
     *
     * @throws MassAssignmentException
     * @return UsageLicense
     */
    public function fillFromRequest(UsageLicense $usageLicense, Request $request): License
    {
        $license = $usageLicense->license;

        $usageLicense->invoice_number = $request->input('invoiceNumber');
        $usageLicense->invoice_number_by = $request->input('invoiceNumberBy');


        $media = $request->get('mediaId') ? (new Media())->where('id',
            $request->input('mediaId'))->firstOrFail() : $license->media;

        switch ($license->license_type) {
            case $license::RF:
                $usageLicense->printrun = $request->input('printrun');
                break;
            case $license::RM:
                $usageLicense->usage = $request->input('usage');
                $usageLicense->printrun = $request->input('printrun');
                $usageLicense->start_at = Carbon::parse($request->input('startDate'));
                $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
                $usageLicense->any_limitations = $request->input('anyLimitations');
                break;
            case $license::RE:
                $usageLicense->start_at = Carbon::parse($request->input('startDate'));
                $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
                $usageLicense->territory = $request->input('territory');
                break;
            case $license::BO:
                $usageLicense->start_at = Carbon::parse($request->input('startDate'));
                $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
                $usageLicense->any_limitations = $request->input('anyLimitations');
                break;
        }

        if ((bool)$request->input('removeBill', false)) {
            $usageLicense->bill_file = null;
            $usageLicense->bill_file_origin_name = null;
        }

        $addFileStatus = false;

        if ($request->hasFile('billFile')) {
            $file = $request->file('billFile');
            $addFileStatus = $file->storeAs($media->brand->getImagePath(), $file->hashName(), 'bill_licenses');
        }

        if ($addFileStatus && $request->hasFile('billFile')) {
            $file = $request->file('billFile');

            if ($file) {
                $usageLicense->bill_file = $file->hashName();
                $usageLicense->bill_file_origin_name = $file->getClientOriginalName();
            }
        }

        $usageLicense->license_id = $license->id;
        $usageLicense->save();

        return $usageLicense;
    }

    /**
     * @param \App\Models\License $license
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Models\License
     */
    public function editUnprocessedLicense(License $license, Request $request) {
        $usageLicense = UsageLicense::where('license_id', $license->id)->first();
        if(!$usageLicense) {
            $usageLicense = new UsageLicense();
        }

        $license->fill([
            'license_type' => (int)$request->input('type'),
            'invoice_number' => $request->input('invoiceNumber'),
            'invoice_number_by' => $request->input('invoiceNumberBy'),
            'media_id' => $request->input('mediaId')
        ]);

        $usageLicense->invoice_number = $request->input('invoiceNumber');
        $usageLicense->invoice_number_by = $request->input('invoiceNumberBy');

        $media = $request->get('mediaId') ? (new Media())->where('id',
            $request->input('mediaId'))->firstOrFail() : $license->media;

        switch ((int)$request->input('type')) {
            case $license::RF:
                $license->fill([
                    'printrun' => $request->input('printrun')
                ]);
                $usageLicense->printrun = $request->input('printrun');
                break;
            case $license::RM:
                $license->fill([
                    'usage' => $request->input('usage'),
                    'printrun' => $request->input('printrun'),
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'any_limitations' => $request->input('anyLimitations'),
                ]);
                $usageLicense->usage = $request->input('usage');
                $usageLicense->printrun = $request->input('printrun');
                $usageLicense->start_at = Carbon::parse($request->input('startDate'));
                $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
                $usageLicense->any_limitations = $request->input('anyLimitations');
                break;
            case $license::RE:
                $license->fill([
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'territory' => $request->input('territory'),
                ]);
                $usageLicense->start_at = Carbon::parse($request->input('startDate'));
                $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
                $usageLicense->territory = $request->input('territory');
                break;
            case $license::BO:
                $license->fill([
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'any_limitations' => $request->input('anyLimitations'),
                ]);
                $usageLicense->start_at = Carbon::parse($request->input('startDate'));
                $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
                $usageLicense->any_limitations = $request->input('anyLimitations');
                break;
        }

        if ((bool)$request->input('removeBill', false)) {
            $license->bill_file = null;
            $license->bill_file_origin_name = null;
            $usageLicense->bill_file = null;
            $usageLicense->bill_file_origin_name = null;
        }

        $addFileStatus = false;
        if ($request->hasFile('billFile')) {
            $file = $request->file('billFile');
            $addFileStatus = $file->storeAs($media->brand->getImagePath(), $file->hashName(), 'bill_licenses');
        }

        if ($addFileStatus && $request->hasFile('billFile')) {
            $file = $request->file('billFile');

            if ($file) {
                $license->bill_file_origin_name = $file->getClientOriginalName();
                $license->bill_file = $file->hashName();
                $usageLicense->bill_file = $file->hashName();
                $usageLicense->bill_file_origin_name = $file->getClientOriginalName();
            }
        }


        $license->save();

        $usageLicense->license_id = $license->id;
        $usageLicense->save();

        return $license;
    }

    /**
     * @param Request $request
     *
     * @throws MassAssignmentException
     * @return License
     */
    public function createFromRequest(Request $request): License
    {
        $license = new License();
        $usageLicense = new UsageLicense();

        $license->fill([
            'license_type' => (int)$request->input('type'),
            'invoice_number' => $request->input('invoiceNumber'),
            'invoice_number_by' => $request->input('invoiceNumberBy'),
            'media_id' => $request->input('mediaId')
        ]);

        $usageLicense->invoice_number = $request->input('invoiceNumber');
        $usageLicense->invoice_number_by = $request->input('invoiceNumberBy');

        $media = $request->get('mediaId') ? (new Media())->where('id',
            $request->input('mediaId'))->firstOrFail() : $license->media;

        switch ((int)$request->input('type')) {
            case $license::RF:
                $license->fill([
                    'printrun' => $request->input('printrun')
                ]);
                $usageLicense->printrun = $request->input('printrun');
                break;
            case $license::RM:
                $license->fill([
                    'usage' => $request->input('usage'),
                    'printrun' => $request->input('printrun'),
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'any_limitations' => $request->input('anyLimitations'),
                ]);
                $usageLicense->usage = $request->input('usage');
                $usageLicense->printrun = $request->input('printrun');
                $usageLicense->start_at = Carbon::parse($request->input('startDate'));
                $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
                $usageLicense->any_limitations = $request->input('anyLimitations');
                break;
            case $license::RE:
                $license->fill([
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'territory' => $request->input('territory'),
                ]);
                $usageLicense->start_at = Carbon::parse($request->input('startDate'));
                $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
                $usageLicense->territory = $request->input('territory');
                break;
            case $license::BO:
                $license->fill([
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'any_limitations' => $request->input('anyLimitations'),
                ]);
                $usageLicense->start_at = Carbon::parse($request->input('startDate'));
                $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
                $usageLicense->any_limitations = $request->input('anyLimitations');
                break;
        }

        if ((bool)$request->input('removeBill', false)) {
            $license->bill_file = null;
            $license->bill_file_origin_name = null;
            $usageLicense->bill_file = null;
            $usageLicense->bill_file_origin_name = null;
        }

        $addFileStatus = false;
        if ($request->hasFile('billFile')) {
            $file = $request->file('billFile');
            $addFileStatus = $file->storeAs($media->brand->getImagePath(), $file->hashName(), 'bill_licenses');
        }

        if ($addFileStatus && $request->hasFile('billFile')) {
            $file = $request->file('billFile');

            if ($file) {
                $license->bill_file_origin_name = $file->getClientOriginalName();
                $license->bill_file = $file->hashName();
                $usageLicense->bill_file = $file->hashName();
                $usageLicense->bill_file_origin_name = $file->getClientOriginalName();
            }
        }


        $license->save();

        $usageLicense->license_id = $license->id;
        $usageLicense->save();

        return $license;
    }
}
