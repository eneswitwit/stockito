<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\License\CreateLicenseRequest;
use App\Http\Requests\License\UpdateLicenseRequest;
use App\ModelManagers\LicenseModelManager;
use App\Models\Brand;
use App\Models\License;
use App\Transformers\LicenseTransformer;
use Barryvdh\DomPDF\PDF;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use LukeVear\LaravelTransformer\TransformerEngine;

class LicensesController extends Controller
{
    /**
     * @param Request $request
     * @param null $brandId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function index (Request $request, $brandId = null) : JsonResponse
    {
        $user = $request->user();
        $licenses = new Collection();
        //if ($user->brand) {
        $brand = $user->brand ?? Brand::find($brandId);
        if (!$brand) {
            return new JsonResponse($licenses);
        }
        $licenses = (new License)->whereIn('id', $brand->media->pluck('license')->pluck('id')->toArray())->orderBy('expired_at', 'ASC')->get();
        /*} elseif ($user->creative) {
            foreach ($user->creative->brands as $brand) {
                $licenses = $licenses->merge((new License)->whereIn('id', $brand->media->pluck('license')->pluck('id')->toArray())->orderBy('expired_at', 'ASC')->get());
            }
        }*/

        return new JsonResponse(new TransformerEngine($licenses, new LicenseTransformer()));
    }

    /**
     * @param License $license
     * @return JsonResponse
     * @throws \Exception
     */
    public function show (License $license): JsonResponse
    {
        return new JsonResponse(new TransformerEngine($license, new LicenseTransformer()));
    }

    /**
     * @param CreateLicenseRequest $request
     * @param LicenseModelManager $licenseModelManager
     * @throws \Exception
     * @return JsonResponse
     */
    public function create (CreateLicenseRequest $request, LicenseModelManager $licenseModelManager): JsonResponse
    {
        $license = $licenseModelManager->createFromRequest($request);
        $media = $license->mediaBelongs;
        $media->license_id = $license->id;
        $media->save();
        return new JsonResponse(new TransformerEngine($license, new LicenseTransformer()));
    }

    /**
     * @param UpdateLicenseRequest $request
     * @param License $license
     * @return JsonResponse
     * @throws \Exception
     */
    public function update (UpdateLicenseRequest $request, License $license, LicenseModelManager $licenseModelManager): JsonResponse
    {
        $license = $licenseModelManager->fillFromRequest($license, $request);
        return new JsonResponse(new TransformerEngine($license, new LicenseTransformer()));
    }

    /**
     * @return JsonResponse
     */
    public function types (): JsonResponse
    {
        return new JsonResponse(License::getLicensesWithTitle());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function soonExpiring (Request $request) : JsonResponse
    {
        $user = $request->user();
        $licenses = new Collection();

        if ($user->brand) {
            $brand = $user->brand;
            $licenses = (new License)->whereIn('id', $brand->media->pluck('license')->pluck('id')->toArray())->where('expired_at', '<>', null)->orderBy('expired_at', 'ASC')->get();
        } elseif ($user->creative) {
            foreach ($user->creative->brands as $brand) {
                $licenses = $licenses->merge((new License)->whereIn('id', $brand->media->pluck('license')->pluck('id')->toArray())->where('expired_at', '<>', null)->orderBy('expired_at', 'ASC')->get());
            }
        }

        return new JsonResponse(new TransformerEngine($licenses->splice(0, 10), new LicenseTransformer()));
    }

    /**
     * @param Request $request
     * @param PDF $pdf
     * @param null $brandId
     *
     * @return Response
     * @throws \Throwable
     */
    public function exportToPdf (Request $request, PDF $pdf, $brandId = null): Response
    {
        $user = $request->user();
        $licenses = new Collection();

        $brand = $user->brand ?? Brand::find($brandId);
        if (!$brand) {
            return new JsonResponse($licenses);
        }
        if ($request->has('ids')) {
            $ids = $request->input('ids', []);
        } else {
            $ids = $brand->media->pluck('license')->pluck('id')->toArray();
        }
        $licenses = (new License)->whereIn('id', $ids)->with('media')->orderBy('expired_at', 'ASC')->get();

        try {
            $view = view('pdf.licenses', ['licenses' => $licenses, 'brand' => $brand])->render();
            $pdf->loadHTML($view)
                ->setPaper('a4', 'landscape');
        } catch(\Exception $e) {
            dd($e->getMessage());
        }
        $filename = strtolower(str_replace([' '], '', $brand->brand_name)) . '-license-export.pdf';
        return $pdf->stream($filename);
    }
}