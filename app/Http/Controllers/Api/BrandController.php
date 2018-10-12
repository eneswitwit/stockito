<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Http\Controllers\Controller;
use App\Mail\CreativeInviteExist;
use App\Mail\CreativeInviteNew;
use App\Models\Brand;
use App\Models\BrandCreative;
use App\Models\User;
use App\Services\UploadService;
use App\Transformers\BrandCreativesTransformer;
use App\Transformers\CategoryTransformer;
use App\Transformers\EditCreativeTransformer;
use App\Transformers\InvoiceTransformer;
use App\Transformers\PeopleAttributeTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LukeVear\LaravelTransformer\TransformerEngine;
use Illuminate\Support\Facades\Mail;

/**
 * Class BrandController
 *
 * @package App\Http\Controllers\Api
 */
class BrandController extends Controller
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getBrandInvoices(Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $invoices = $user->getInvoices();

        return new JsonResponse(new TransformerEngine($invoices, new InvoiceTransformer()));
    }

    /**
     * @param Request $request
     *
     * @param $brandId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getBrandCreatives(Request $request, $brandId = null): JsonResponse
    {
        $user = $request->user();
        $brand = $user->brand ?? Brand::find($brandId);
        $creatives = $brand->creatives()->get();

        return new JsonResponse(new TransformerEngine($creatives, new BrandCreativesTransformer()));
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getBrandCreative(Request $request, $id): JsonResponse
    {
        $barndId = BrandCreative::whereCreativeId($id)->firstOrFail()->brand_id;
        $user = $request->user();
        $brand = $user->brand ?? Brand::find($barndId);
        $creative = $brand->creatives()->where('creative_id', $id)->firstOrFail();

        return new JsonResponse(new TransformerEngine($creative, new EditCreativeTransformer()));
    }

    /**
     * @param Request $request
     *
     * @param $brandId
     *
     * @return JsonResponse
     */
    public function inviteCreative(Request $request, $brandId = null): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'role' => 'required|string',
            'position' => 'required'
        ]);

        $user = $request->user();
        /**
         * @var Brand $brand
         */
        $brand = $user->brand ?? Brand::find($brandId);
        $creativeUser = User::where('email', $request->email)->first();

        if ($creativeUser) {
            $creative = $creativeUser->creative;
            $brand->creatives()->attach($creative, [
                'role' => $request->role,
                'position' => $request->position
            ]);
            Mail::to($request->email)->send(new CreativeInviteExist($brand->brand_name, $request->role));
        } else {
            Mail::to($request->email)->send(new CreativeInviteNew($brand, $request->role, $request->position));
        }

        return new JsonResponse(['success' => true]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function editCreative(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required',
            'role' => 'required|string',
            'position' => 'required'
        ]);

        $user = $request->user();
        $barndId = BrandCreative::whereCreativeId($request->id)->firstOrFail()->brand_id;
        $brand = $user->brand ?? Brand::find($barndId);

        if ($creative = $brand->creatives()->where('creative_id', $request->id)->first()) {
            $creative->pivot->update([
                'role' => $request->role,
                'position' => $request->position
            ]);
        }

        return new JsonResponse(['success' => true]);
    }

    /**
     * @param Request $request
     * @param UploadService $uploadService
     *
     * @return JsonResponse
     */
    public function getUsedStorage(Request $request, UploadService $uploadService): JsonResponse
    {
        /**
         * @var Brand $brand
         */
        $brand = $request->user()->brand;

        return new JsonResponse($uploadService::formatedUsedStorage($brand));
    }

    /**
     * @param Brand $brand
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getPeopleAttributes(Brand $brand): JsonResponse
    {
        return new JsonResponse(new TransformerEngine($brand->peopleAttributes, new PeopleAttributeTransformer()));
    }

    /**
     * @param Brand $brand
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getSuppliers(Brand $brand): JsonResponse
    {
        return new JsonResponse(new TransformerEngine($brand->suppliers, new PeopleAttributeTransformer()));
    }

    /**
     * @param Brand $brand
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getCategories(Brand $brand): JsonResponse
    {
        return new JsonResponse(new TransformerEngine($brand->categories, new CategoryTransformer()));
    }
}