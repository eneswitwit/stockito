<?php

namespace App\ModelManagers;

use App\Models\License;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\Request;

class LicenseModelManager
{
    protected $model = License::class;

    /**
     * @param License $license
     * @param Request $request
     * @throws MassAssignmentException
     * @return License
     */
    public function fillFromRequest(License $license, Request $request): License
    {
        $license->fill([
            'license_type' => (int)$request->input('type'),
            'invoice_number' => $request->input('invoiceNumber'),
            'invoice_number_by' => $request->input('invoiceNumberBy'),
            'media_id' => $request->input('mediaId')
        ]);

        $media = $request->get('mediaId') ? (new Media())->where('id', $request->input('mediaId'))->firstOrFail() : $license->media;

        switch ((int)$request->input('type')) {
            case $license::RF:
                $license->fill([
                    'printrun' => $request->input('printrun')
                ]);
                break;
            case $license::RM:
                $license->fill([
                    'usage' => $request->input('usage'),
                    'printrun' => $request->input('printrun'),
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'any_limitations' => $request->input('anyLimitations'),
                ]);
                break;
            case $license::RE:
                $license->fill([
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'territory' => $request->input('territory'),
                ]);
                break;
            case $license::BO:
                $license->fill([
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'any_limitations' => $request->input('anyLimitations'),
                ]);
                break;
        }

        if ((bool)$request->input('removeBill', false)) {
            $license->bill_file = null;
            $license->bill_file_origin_name = null;
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
            }
        }

        $license->save();
        return $license;
    }

    /**
     * @param Request $request
     * @throws MassAssignmentException
     * @return License
     */
    public function createFromRequest (Request $request): License
    {
        return $this->fillFromRequest(new License(), $request);
    }
}
