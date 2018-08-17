<?php

namespace App\Http\Controllers\Api;

use App\Events\DeletedFileEvent;
use App\Events\EditedFileEvent;
use App\Events\UploadedFileEvent;
use App\Http\Requests\License\CreateLicenseRequest;
use App\Http\Requests\Media\ShareMediaToEmail;
use App\Http\Requests\Media\UploadRequest;
use App\Jobs\ProcessFTPFile;
use App\Mail\ShareMediaMail;
use App\Managers\FTPFilesManager;
use App\Models\Brand;
use App\ModelManagers\LicenseModelManager;
use App\Models\FTPFile;
use App\Models\License;
use App\Models\Media;
use App\Models\PeopleAttribute;
use App\Models\Share;
use App\Models\Supplier;
use App\Models\User;
use App\Services\UploadService;
use App\Transformers\BrandCreativesTransformer;
use App\Transformers\MediaExtendedTransformer;
use App\Transformers\MediaTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use LukeVear\LaravelTransformer\TransformerEngine;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    /**
     * @var UploadService
     */
    protected $uploadService;

    /**
     * @var FTPFilesManager
     */
    protected $ftpFilesManager;

    /**
     * MediaController constructor.
     * @param FTPFilesManager $ftpFilesManager
     * @param UploadService $uploadService
     */
    public function __construct(FTPFilesManager $ftpFilesManager, UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
        $this->ftpFilesManager = $ftpFilesManager;
    }

    /**
     * @param null $brandId
     * @return JsonResponse
     */
    public function processing($brandId = null): JsonResponse
    {
        /**
         * @var User $user
         * @var Collection $medias
         */
        $user = auth()->user();
        $brand = $user->brand ?? Brand::find($brandId);
        if(!$brand) {
            return new JsonResponse();
        }

        $result['processing'] = (new FTPFile())::notHandled()->where('username', $brand->ftpUser->userid)->where('processing', true)->count();
        $result['queuing'] = (new FTPFile())::notHandled()->where('username', $brand->ftpUser->userid)->where('queuing', true)->count();

        return new JsonResponse($result);
    }

    /**
     * @return JsonResponse
     */
    public function categories(): JsonResponse
    {
        $categories = Media\Category::all();

        return new JsonResponse($categories);
    }

    /**
     * @return JsonResponse
     */
    public function types(): JsonResponse
    {
        return new JsonResponse(Media::$types);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user  = auth()->user();
        $brand = $user->brand;
        if ( ! $brand && $request->has('selectedBrand')) {
            $brand = Brand::findOrFail($request->selectedBrand);
        }

        if(!$brand) {
            return new JsonResponse();
        }

        $medias = $brand->media()->published()->orderBy('created_at', 'decs');

        if ($request->input('licenseType', false)) {
            $medias->whereHas('license', function (Builder $q) use ($request) {
                $q->where('license_type', $request->input('licenseType'));
            });
        }

        if ($request->input('categoryId', false)) {
            $medias->where('category_id', $request->input('categoryId'));
        }

        if ($request->input('peopleAttributes', false) && $request->input('peopleAttributes.0') != false) {
            $medias->whereHas('peopleAttributes', function (Builder $builder) use ($request) {
                $builder->whereIn('people_attributes.id', $request->input('peopleAttributes'));
            });
        }

        if ($request->input('supplierId', false)) {
            $medias->where('supplier_id', $request->input('supplierId'));
        }

        if ($request->input('orientation', false)) {
            $medias->where('orientation', $request->input('orientation'));
        }

        if ($request->input('q', false)) {
            $medias->where(function (Builder $builder) use ($request) {
                $builder->orWhere('keywords', 'LIKE' , '%'.$request->input('q').'%')
                    ->orWhere('source', 'LIKE', '%'.$request->input('q').'%')
                    ->orWhere('origin_name', 'LIKE', $request->input('q').'%')
                    ->orWhere('notes', 'LIKE', '%'.$request->input('q').'%')
                    ->orWhere('title', 'LIKE', '%'.$request->input('q').'%');
            });
        }

        $medias = $medias->get();

        return new JsonResponse(new TransformerEngine($medias, new MediaTransformer()));
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getBrandMedias(Request $request, $id): JsonResponse
    {
        $creative = $request->user()->creative;
        $brand    = Brand::find($id);
        if ( ! $brand) {
            throw new \Exception('Brand not found!');
        }
        $brandCreative = $brand->creatives()->where('creatives.id', $creative->id)->first();
        if ( ! $brandCreative) {
            throw new \Exception('Creative not in brand team!');
        }
        $medias = $brand->media()->published()->orderBy('created_at', 'decs')->get();

        $brandCreativeResponse = new TransformerEngine($brandCreative, new BrandCreativesTransformer());
        $response              = new TransformerEngine($medias, new MediaTransformer());

        return new JsonResponse(['medias' => $response, 'creativeRole' => $brandCreativeResponse]);
    }

    /**
     * @param null $brandId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function uploads($brandId = null): JsonResponse
    {
        /**
         * @var User $user
         * @var Collection $medias
         * @var FTPFile[] $ftpFiles
         */
        $user = auth()->user();
        $brand = $user->brand ?? Brand::find($brandId);
        if(!$brand) {
            return new JsonResponse();
        }

        $ftpFiles = (new FTPFile())
            ->where('queuing', false)
            ->where('processing', false)
            ->where('username', $brand->ftpUser->userid)
            ->get();

        $ftpFiles->each(function (FTPFile $FTPFile) {
            $FTPFile->queuing = true;
            $FTPFile->save();
            ProcessFTPFile::dispatch($FTPFile);
        });

        $medias = $brand->media()->notPublished()->orderBy('created_at', 'decs')->get();

        $medias->map(function (Media $media) {
            if (!Storage::disk('brands')->exists($media->getFilePath())) {
                $media->delete();
            }
        });

        return new JsonResponse(new TransformerEngine($medias, new MediaTransformer()));
    }

    /**
     * @param UploadRequest $request
     *
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function upload(UploadRequest $request)
    {
        /**
         * @var User $user
         */
        $user  = $request->user();
        $brand = $user->brand ?? Brand::find($request->brandId);
        try {
            $media = $this->uploadService->uploadMedia($request, $brand);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), 401);
        }

        event(new UploadedFileEvent($media, $brand));

        return new JsonResponse([
            'success' => true,
            'data'    => new TransformerEngine($media, new MediaTransformer())
        ]);
    }

    /**
     * @param Media $media
     *
     * @return StreamedResponse
     */
    public function download(Media $media): StreamedResponse
    {
        return Storage::disk('brands')->download($media->dir . '/' . $media->file_name);
    }

    /**
     * @param Request $request
     * @param Media $media
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function submit(Request $request, Media $media): JsonResponse
    {
        $this->validate($request, [
            'title' => 'required',
        ]);
        /**
         * @var Supplier $supplier
         * @var Media\Category $category
         */
        if ($request->input('category.label', false)) {
            $category           = Media\Category::firstOrCreate(['name' => $request->input('category.label')], ['brand_id' => $media->brand->id]);
            $media->category_id = $category->id;
        }

        if ($request->input('category', false)) {
            $category           = Media\Category::firstOrCreate(['name' => $request->input('category')], ['brand_id' => $media->brand->id]);
            $media->category_id = $category->id;
        }

        if ($request->input('supplier.label', false)) {
            $supplier           = Supplier::firstOrCreate(['name' => $request->input('supplier.label')], ['brand_id' => $media->brand->id]);
            $media->supplier_id = $supplier->id;
        }

        if ($request->input('supplier', false)) {

            $supplier           = Supplier::firstOrCreate(['name' => $request->input('supplier')], ['brand_id' => $media->brand->id]);
            $media->supplier_id = $supplier->id;
        }

        if ($request->input('peopleAttributes', false)) {
            $attributes = new Collection();
            if (\is_array($request->input('peopleAttributes'))) {
                foreach ($request->input('peopleAttributes') as $key => $value) {
                    if (\is_string($value)) {
                        $attributes->push(PeopleAttribute::firstOrCreate(['name' => $value], ['brand_id' => $media->brand->id]));
                    } elseif (\is_array($value)) {
                        if (isset($value['label'])) {
                            $attributes->push(PeopleAttribute::firstOrCreate(['name' => $value['label']], ['brand_id' => $media->brand->id]));
                        }
                    }
                }
            }
            $media->peopleAttributes()->sync($attributes->pluck('id')->toArray(), true);
        }

        $media->title       = $request->input('title', '');
        $media->file_type   = $request->input('fileType', '');
        $media->keywords    = $request->input('keywords', '');
        $media->source      = $request->input('source', '');
        $media->language    = $request->input('language', '');
        $media->origin_name = $request->input('originName', '');

        $media->publish()->save();
        event(new EditedFileEvent($media, $media->brand));

        return new JsonResponse(new TransformerEngine($media, new MediaTransformer()));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function share(Request $request): JsonResponse
    {
        $type = $request->input('type');

        $share = new Share([
            'type' => $type,
            'hash' => str_random(32)
        ]);

        if ($type === Share::TIME_LIMITED_LINK) {
            $days              = $request->input('days');
            $share->expires_at = Carbon::now()->addDay($days)->endOfDay();
        }

        $share->save();

        $share->medias()->attach($request->input('medias'));

        return new JsonResponse(['success' => true, 'data' => ['link' => $share->getLink()]]);
    }

    /**
     * @param ShareMediaToEmail $request
     *
     * @return JsonResponse
     */
    public function shareEmail(ShareMediaToEmail $request): JsonResponse
    {
        $medias = (new Media())->whereIn('id', $request->input('medias'))->get();
        Mail::to($request->input('email'))->send(new ShareMediaMail($medias, $request->input('text')));

        return new JsonResponse(['success' => true]);
    }

    /**
     * @param Media $media
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function show(Media $media): JsonResponse
    {
        return new JsonResponse(new TransformerEngine($media, new MediaExtendedTransformer()));
    }

    /**
     * @param Request $request
     * @param Media $media
     * @return JsonResponse
     */
    public function update(Request $request, Media $media): JsonResponse
    {
        if ($request->input('category.label', false)) {
            $category           = Media\Category::firstOrCreate(['name' => $request->input('category.label')]);
            $media->category_id = $category->id;
        }

        $media->file_type   = $request->input('fileType', '');
        $media->keywords    = $request->input('keywords', '');
        $media->source      = $request->input('source', '');
        $media->language    = $request->input('language', '');
        $media->origin_name = $request->input('originName', '');
        $media->notes       = $request->input('notes', '');

        $media->save();

        event(new EditedFileEvent($media, $media->brand));

        return new JsonResponse(new TransformerEngine($media, new MediaExtendedTransformer()));
    }

    /**
     * @param Media $media
     * @param UploadService $uploadService
     * @return JsonResponse
     */
    public function remove(Media $media, UploadService $uploadService): JsonResponse
    {
        try {
            $status = $uploadService->removeMedia($media);
            if (!$status) {
                throw new \Exception('Can\'t remove the file');
            }
        } catch (\Exception $exception) {
            return new JsonResponse(['success' => false, 'message' => $exception->getMessage()]);
        }
        event(new DeletedFileEvent($media, $media->brand));

        return new JsonResponse(['success' => true, 'message' => 'Media file has been deleted']);
    }

    /**
     * @param CreateLicenseRequest $request
     * @param Media $media
     * @param LicenseModelManager $licenseModelManager
     *
     * @throws \Exception
     * @return JsonResponse
     */
    public function addLicense(
        CreateLicenseRequest $request,
        Media $media,
        LicenseModelManager $licenseModelManager
    ): JsonResponse {
        $license = null;

        if ($request->input('id', false)) {
            $license = (new License)->where('id', $request->input('id'))->firstOrFail();
            $license = $licenseModelManager->fillFromRequest($license, $request);
        }

        if ( ! $license) {
            $license = $licenseModelManager->createFromRequest($request);
            $media->license()->associate($license);
        }

        $media->save();

        return new JsonResponse(new TransformerEngine($media, new MediaTransformer()));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function downloadMultiple(Request $request)
    {
        $media = (new Media)->whereIn('id', $request->input('media'))->get();

        $fileName = str_random();
        $zip      = new \ZipArchive();
        $path     = 'zips/' . $fileName . '.zip';
        $filename = storage_path('app/brands/' . $path);

        if ($zip->open($filename, \ZipArchive::CREATE) !== true) {
            exit("cannot open <$filename>\n");
        }

        foreach ($media as $mediaItem) {
            /**
             * @var Media $mediaItem
             */
            $zip->addFile(storage_path('app/brands/' . $mediaItem->dir . '/' . $mediaItem->file_name),
                $mediaItem->origin_name);
        }

        $zip->close();

        return new JsonResponse(['url' => route('download', ['hash' => $fileName])]);
    }
}
