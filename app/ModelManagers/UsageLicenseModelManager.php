<?php

// namespace
namespace App\ModelManagers;

// use
use App\Models\UsageLicense;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\Request;

/**
 * Class LicenseModelManager
 *
 * @package App\ModelManagers
 */
class UsageLicenseModelManager
{
    protected $model = UsageLicense::class;

    /**
     * @param UsageLicense $usageLicense
     * @param Request $request
     *
     * @throws MassAssignmentException
     * @return UsageLicense
     */
    public function fillFromRequest(UsageLicense $usageLicense, Request $request): UsageLicense
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

}
