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
use App\Transformers\MinorBrandTransformer;
use App\Transformers\CategoryTransformer;
use App\Transformers\EditCreativeTransformer;
use App\Transformers\InvoiceTransformer;
use App\Transformers\PeopleAttributeTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LukeVear\LaravelTransformer\TransformerEngine;
use Illuminate\Support\Facades\Mail;
use App\Services\FTPService;
use Log;

/**
 * Class BrandController
 *
 * @package App\Http\Controllers\Api
 */
class BrandController extends Controller
{
    /**
     * @param $selectedBrandId
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getBrand($selectedBrandId): JsonResponse
    {

        $brand = Brand::find($selectedBrandId);
        return new JsonResponse(new TransformerEngine($brand, new MinorBrandTransformer()));
    }

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
    public function getBrandCreative(Request $request, $id, $brandId = null): JsonResponse
    {
        $brand = $request->user()->brand ?? Brand::find($brandId);
        $creative = $brand->creatives()->where('creative_id', $id)->firstOrFail();
        $creative['brandCreativeId'] = $creative->brandCreative($brand->id)->id;

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
        $email = $request['email'];
        $creativeUser = User::where('email', '=', strtolower($email))->first();

        if ($creativeUser && $creativeUser->creative) {
            $creativeUserCreative = $creativeUser->creative;
            if (!BrandCreative::where('brand_id', $brand->id)->where('creative_id', $creativeUserCreative->id)->exists()) {
                $brand->creatives()->attach($creativeUserCreative, [
                    'role' => $request->role,
                    'position' => $request->position
                ]);

                FTPService::makeFTPUserForBrandCreative($brand,  $creativeUserCreative);

                Mail::to($request->email)->send(new CreativeInviteExist($brand->brand_name, $request->role));
            }
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
            'brandCreativeId' => 'required',
            'role' => 'required|string',
            'position' => 'required'
        ]);

        $brandCreative = BrandCreative::findOrFail($request->brandCreativeId);
        $brandCreative->role = $request->role;
        $brandCreative->position = $request->position;
        $brandCreative->save();

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