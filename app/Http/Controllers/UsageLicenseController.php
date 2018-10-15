<?php

// namespace
namespace App\Http\Controllers;

// use
use App\Http\Requests\License\CreateLicenseRequest;
use App\Models\UsageLicense;
use App\Models\License;
use App\Transformers\UsageLicenseTransformer;
use Illuminate\Http\JsonResponse;
use LukeVear\LaravelTransformer\TransformerEngine;
use Carbon\Carbon;

/**
 * Class UsageLicenseController
 *
 * @package App\Http\Controllers
 */
class UsageLicenseController extends Controller
{

    /**
     * @param  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(CreateLicenseRequest $request): JsonResponse
    {
        $user = auth()->user();

        $license = License::find($request->get('licenseId'));
        $media = $license->media;

        $usageLicense = UsageLicense::firstOrNew(['id' => $request->get('id')]);
        $usageLicense->license_id = $license->id;
        $usageLicense->invoice_number = $request->input('invoiceNumber', null);
        $usageLicense->invoice_number_by = $request->input('invoiceNumberBy', null);
        $usageLicense->printrun = $request->input('printrun', null);
        $usageLicense->usage = $request->input('usage', null);
        $usageLicense->printrun = $request->input('printrun', null);
        $usageLicense->start_at = Carbon::parse($request->input('startDate'));
        $usageLicense->expired_at = Carbon::parse($request->input('expireDate'));
        $usageLicense->any_limitations = $request->input('anyLimitations', null);
        $usageLicense->territory = $request->input('territory', null);
        $usageLicense->created_by = $user->id;
        $usageLicense->updated_by = $user->id;

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

        $usageLicense->save();
        return new JsonResponse(new TransformerEngine($usageLicense, new UsageLicenseTransformer()));
    }

}
