<?php

// namespace
namespace App\Http\Controllers\Api;

// use
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
use LukeVear\LaravelTransformer\TransformerEngine;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;
use Log;


/**
 * Class MediaController
 *
 * @package App\Http\Controllers\Api
 */
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
     *
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
     *
     * @return JsonResponse
     */
    public function processing($brandId = null): JsonResponse
    {

        /**
         * @var User $user
         * @var Collection $medias
         */


        $user = auth()->user();
        $brand = $user->brand;
        $creative = $user->creative;


        if ($creative && !$brand) {

            $creativeBrand = Brand::find($brandId);
            if ($creativeBrand) {
                $result['processing'] = (new FTPFile())::notHandled()->where('username',
                    $creative->getFTPUsername($creativeBrand))->where('processing', true)->count();

                $result['queuing'] = (new FTPFile())::notHandled()->where('username',
                    $creative->getFTPUsername($creativeBrand))->where('queuing',
                    true)->count();

            } else {
                return new JsonResponse();
            }
        } else {
            if ($brand && !$creative) {


                $result['processing'] = (new FTPFile())::notHandled()->where('username',
                    $brand->ftpUser->userid)->where('processing', true)->count();

                $result['queuing'] = (new FTPFile())::notHandled()->where('username',
                    $brand->ftpUser->userid)->where('queuing',
                    true)->count();


            } else {

                return new JsonResponse();
            }
        }

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
        $user = auth()->user();
        $brand = $user->brand;
        if (!$brand && $request->has('selectedBrand')) {
            $brand = Brand::findOrFail($request->selectedBrand);
        }

        if (!$brand) {
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

        if ($request->input('peoplesAttribute', false)) {
            $medias->where('peoples_attribute', $request->input('peoplesAttribute'));
        }

        if ($request->input('supplierId', false)) {
            $medias->where('supplier_id', $request->input('supplierId'));
        }

        if ($request->input('orientation', false)) {
            $medias->where('orientation', $request->input('orientation'));
        }

        if ($request->input('q', false)) {
            $medias->where(function (Builder $builder) use ($request) {
                $builder->orWhere('keywords', 'LIKE', '%' . $request->input('q') . '%')
                    ->orWhere('source', 'LIKE', '%' . $request->input('q') . '%')
                    ->orWhere('origin_name', 'LIKE', $request->input('q') . '%')
                    ->orWhere('notes', 'LIKE', '%' . $request->input('q') . '%')
                    ->orWhere('title', 'LIKE', '%' . $request->input('q') . '%');
            });
        }

        $medias = $medias->get();

        return new JsonResponse(new TransformerEngine($medias, new MediaTransformer()));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function indexStep(Request $request, $taken, $toTake): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = auth()->user();
        $brand = $user->brand;
        if (!$brand && $request->has('selectedBrand')) {
            $brand = Brand::findOrFail($request->selectedBrand);
        }

        if (!$brand) {
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

        if ($request->input('peoplesAttribute', false)) {
            $medias->where('peoples_attribute', $request->input('peoplesAttribute'));
        }

        if ($request->input('supplierId', false)) {
            $medias->where('supplier_id', $request->input('supplierId'));
        }

        if ($request->input('orientation', false)) {
            $medias->where('orientation', $request->input('orientation'));
        }

        if ($request->input('q', false)) {
            $medias->where(function (Builder $builder) use ($request) {
                $builder->orWhere('keywords', 'LIKE', '%' . $request->input('q') . '%')
                    ->orWhere('source', 'LIKE', '%' . $request->input('q') . '%')
                    ->orWhere('origin_name', 'LIKE', '%' . $request->input('q') . '%')
                    ->orWhere('notes', 'LIKE', '%' . $request->input('q') . '%')
                    ->orWhere('title', 'LIKE', '%' . $request->input('q') . '%');
            });
        }

        $medias = $medias->skip($taken)->take($toTake)->get();

        $medias->map(function (Media $media) {
            if (!Storage::disk('s3')->exists($media->getFilePath())) {
                $media->delete();
            }
        });

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
        $brand = Brand::find($id);
        if (!$brand) {
            throw new \Exception('Brand not found!');
        }
        $brandCreative = $brand->creatives()->where('creatives.id', $creative->id)->first();
        if (!$brandCreative) {
            throw new \Exception('Creative not in brand team!');
        }
        $medias = $brand->media()->published()->orderBy('created_at', 'desc')->get();

        $brandCreativeResponse = new TransformerEngine($brandCreative, new BrandCreativesTransformer());
        $response = new TransformerEngine($medias, new MediaTransformer());

        return new JsonResponse(['medias' => $response, 'creativeRole' => $brandCreativeResponse]);
    }

    /**
     * @param Request $request
     * @param int $taken
     * @param int $toTake
     * @param $id
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getBrandMediasStep(Request $request, $taken, $toTake, $id): JsonResponse
    {
        $creative = $request->user()->creative;
        $brand = Brand::find($id);
        if (!$brand) {
            throw new \Exception('Brand not found!');
        }
        $brandCreative = $brand->creatives()->where('creatives.id', $creative->id)->first();
        if (!$brandCreative) {
            throw new \Exception('Creative not in brand team!');
        }
        $medias = $brand->media()->published()->orderBy('created_at', 'desc')->skip($taken)->take($toTake)->get();

        $brandCreativeResponse = new TransformerEngine($brandCreative, new BrandCreativesTransformer());
        $response = new TransformerEngine($medias, new MediaTransformer());

        return new JsonResponse(['medias' => $response, 'creativeRole' => $brandCreativeResponse]);
    }

    /**
     * @param null|int $brandId
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
        $brand = $user->brand;
        $creative = $user->creative;

        if ($brand && !$creative) {

            $ftpFiles = (new FTPFile())
                ->where('handled', false)
                ->where('processing', false)
                ->where('username', $brand->ftpUser->userid)
                ->get();

            $ftpFiles->each(function (FTPFile $FTPFile) {

                $FTPFile->queuing = true;
                $FTPFile->save();

                $processFTP = new ProcessFTPFile($FTPFile);
                $processFTP->handle($this->ftpFilesManager);

                /*$this->ftpFilesManager->handleFTPFile($FTPFile);
                $FTPFile->queuing = true;
                $FTPFile->save();*/
                ProcessFTPFile::dispatch($FTPFile);
            });


        } else {
            if (!$brand && $creative) {

                $brand = Brand::find($brandId);
                if ($brand) {

                    $ftpFiles = (new FTPFile())
                        ->where('handled', false)
                        ->where('processing', false)
                        ->where('username', $creative->getFTPUsername($brand))
                        ->get();


                    $ftpFiles->each(function (FTPFile $FTPFile) {

                        $FTPFile->queuing = true;
                        $FTPFile->save();

                        $processFTP = new ProcessFTPFile($FTPFile);
                        $processFTP->handle($this->ftpFilesManager);

                        /*$this->ftpFilesManager->handleFTPFile($FTPFile);
                        $FTPFile->queuing = true;
                        $FTPFile->save();*/
                        ProcessFTPFile::dispatch($FTPFile);
                    });
                } else {
                    return new JsonResponse();
                }

            } else {
                return new JsonResponse();
            }
        }

        if ($brand) {
            $medias = $brand->media()->where('created_by', $user->id)->notPublished()->orderBy('created_at',
                'desc')->get();
        } else {
            return new JsonResponse();
        }

        $medias->map(function (Media $media) {
            if (!Storage::disk('s3')->exists($media->getFilePath())) {
                $media->delete();
            }
        });

        return new JsonResponse(new TransformerEngine($medias, new MediaTransformer()));
    }

    /**
     * @param null|int $brandId
     * @param int $taken
     * @param int $toTake
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getUploads($taken, $toTake, $brandId = null): JsonResponse
    {

        /**
         * @var User $user
         * @var Collection $medias
         * @var FTPFile[] $ftpFiles
         */
        $user = auth()->user();
        $brand = $user->brand;
        $creative = $user->creative;

        if ($brand && !$creative) {

            $ftpFiles = (new FTPFile())
                ->where('handled', false)
                ->where('processing', false)
                ->where('username', $brand->ftpUser->userid)
                ->get();

            $ftpFiles->each(function (FTPFile $FTPFile) {

                $FTPFile->queuing = true;
                $FTPFile->save();

                $processFTP = new ProcessFTPFile($FTPFile);
                $processFTP->handle($this->ftpFilesManager);

                /*$this->ftpFilesManager->handleFTPFile($FTPFile);
                $FTPFile->queuing = true;
                $FTPFile->save();*/
                ProcessFTPFile::dispatch($FTPFile);
            });


        } else {
            if (!$brand && $creative) {

                $brand = Brand::find($brandId);
                if ($brand) {

                    $ftpFiles = (new FTPFile())
                        ->where('handled', false)
                        ->where('processing', false)
                        ->where('username', $creative->getFTPUsername($brand))
                        ->get();


                    $ftpFiles->each(function (FTPFile $FTPFile) {

                        $FTPFile->queuing = true;
                        $FTPFile->save();

                        $processFTP = new ProcessFTPFile($FTPFile);
                        $processFTP->handle($this->ftpFilesManager);

                        /*$this->ftpFilesManager->handleFTPFile($FTPFile);
                        $FTPFile->queuing = true;
                        $FTPFile->save();*/
                        ProcessFTPFile::dispatch($FTPFile);
                    });
                } else {
                    return new JsonResponse();
                }

            } else {
                return new JsonResponse();
            }
        }

        if ($brand) {
            $medias = $brand->media()
                ->where('created_by', $user->id)
                ->where('published', false)
                ->orderBy('created_at', 'desc')
                ->skip($taken)
                ->take($toTake)
                ->get();
            if(sizeof($medias) === 0) {
                return new JsonResponse();
            }

        } else {
            return new JsonResponse();
        }

        $medias->map(function (Media $media) {
            if (!Storage::disk('s3')->exists($media->getFilePath())) {
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
        $user = $request->user();
        $brand = $user->brand ?? Brand::find($request->brandId);

        try {
            $media = $this->uploadService->uploadMedia($request, $brand);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), 401);
        }

        event(new UploadedFileEvent($media, $brand));

        return new JsonResponse([
            'success' => true,
            'data' => new TransformerEngine($media, new MediaTransformer())
        ]);
    }

    /**
     * @param Media $media
     *
     * @return StreamedResponse
     */
    public function download(Media $media): StreamedResponse
    {
        return Storage::disk('s3')->download($media->dir . '/' . $media->file_name);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorSubmit(array $data)
    {
        return Validator::make($data, [
            'title' => 'required',
            'keywords' => 'required|min:6|max:1024'
        ]);
    }

    /**
     * @param Request $request
     * @param Media $media
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function submit(Request $request): JsonResponse
    {
        /**
         * @var Supplier $supplier
         * @var Media\Category $category
         */

        $media = (new Media)->where('id', $request->input('mediaId'))->get();

        $this->validatorSubmit($request->all())->validate();

        if ($request->input('category.label', false)) {
            $category = Media\Category::firstOrCreate(
                ['name' => $request->input('category.label'), 'brand_id' => $media->brand->id]
            );
            $media->category_id = $category->id;
        }


        if ($request->input('category',
                false) && is_string($request->input('category')) && $request->input('category') !== '') {
            $category = Media\Category::firstOrCreate(
                ['name' => $request->input('category'), 'brand_id' => $media->brand->id]
            );
            $media->category_id = $category->id;
        }


        if ($request->input('supplier.label', false)) {
            $supplier = Supplier::firstOrCreate(
                ['name' => $request->input('supplier.label'), 'brand_id' => $media->brand->id]
            );
            $media->supplier_id = $supplier->id;
        }


        if ($request->input('supplier',
                false) && is_string($request->input('supplier')) && $request->input('supplier') !== '') {

            $supplier = Supplier::firstOrCreate(
                ['name' => $request->input('supplier'), 'brand_id' => $media->brand->id]
            );
            $media->supplier_id = $supplier->id;
        }


        if ($request->input('peopleAttributes', null)) {
            $media->peoples_attribute = $request->input('peopleAttributes');
        }


        if ($request->input('title') === null) {
            $media->title = $request->input('originName', '');
        } else {
            $media->title = $request->input('title', '');
        }


        $media->file_type = $request->input('fileType', '');
        $media->keywords = $request->input('keywords', '');
        $media->source = $request->input('source', '');
        $media->language = $request->input('language', '');
        $media->origin_name = $request->input('originName', '');


        $media->publish()->save();

        event(new EditedFileEvent($media, $media->brand));

        return new JsonResponse(new TransformerEngine($media, new MediaTransformer()));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function submitMultiple(Request $request): JsonResponse
    {

        $this->validatorSubmit($request->all())->validate();

        $mediaFiles = (new Media)->whereIn('id', $request->input('mediaId'))->get();

        foreach ($mediaFiles as $media) {

            /**
             * @var Supplier $supplier
             * @var Media\Category $category
             */

            if ($request->input('category.label', false)) {
                $category = Media\Category::firstOrCreate(
                    ['name' => $request->input('category.label'), 'brand_id' => $media->brand->id]
                );
                $media->category_id = $category->id;
            }


            if ($request->input('category',
                    false) && is_string($request->input('category')) && $request->input('category') !== '') {
                $category = Media\Category::firstOrCreate(
                    ['name' => $request->input('category'), 'brand_id' => $media->brand->id]
                );
                $media->category_id = $category->id;
            }


            if ($request->input('supplier.label', false)) {
                $supplier = Supplier::firstOrCreate(
                    ['name' => $request->input('supplier.label'), 'brand_id' => $media->brand->id]
                );
                $media->supplier_id = $supplier->id;
            }


            if ($request->input('supplier',
                    false) && is_string($request->input('supplier')) && $request->input('supplier') !== '') {

                $supplier = Supplier::firstOrCreate(
                    ['name' => $request->input('supplier'), 'brand_id' => $media->brand->id]
                );
                $media->supplier_id = $supplier->id;
            }

            if ($request->input('peopleAttributes', null)) {
                $media->peoples_attribute = $request->input('peopleAttributes');
            }

            if ($request->input('title') === null) {
                $media->title = $media->origin_name;
            } else {
                $media->title = $request->input('title', '');
            }
            $media->file_type = $request->input('fileType', '');
            $media->keywords = $request->input('keywords', '');
            $media->source = $request->input('source', '');
            $media->language = $request->input('language', '');

            $media->publish()->save();
            event(new EditedFileEvent($media, $media->brand));
        }

        return new JsonResponse(new TransformerEngine($mediaFiles, new MediaTransformer()));
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
            $days = $request->input('days');
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
     * @param int $id
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function get($id): JsonResponse
    {
        $media = Media::find($id);
        return new JsonResponse(new TransformerEngine($media, new MediaTransformer()));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getMultiple(Request $request): JsonResponse
    {
        $mediaFiles = (new Media)->whereIn('id', $request->input('media'))->get();
        return new JsonResponse(new TransformerEngine($mediaFiles, new MediaTransformer()));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request, Media $media): JsonResponse
    {

        if ($request->input('category.label', false)) {
            $category = Media\Category::firstOrCreate(
                ['name' => $request->input('category.label'), 'brand_id' => $media->brand->id]
            );
            $media->category_id = $category->id;
        }

        if ($request->input('supplier.label', false)) {

            $supplier = Supplier::firstOrCreate(
                ['name' => $request->input('supplier.label'), 'brand_id' => $media->brand->id]
            );
            $media->supplier_id = $supplier->id;
        }

        $media->peoples_attribute = $request->input('peoplesAttribute', null);
        $media->title = $request->input('title', '');
        $media->file_type = Media::getTypeVar($request->input('fileType', ''));
        $media->keywords = $request->input('keywords', '');
        $media->source = $request->input('source', '');
        $media->origin_name = $request->input('originName', '');
        $media->notes = $request->input('notes', '');

        $media->save();

        event(new EditedFileEvent($media, $media->brand));

        return new JsonResponse(new TransformerEngine($media, new MediaExtendedTransformer()));
    }

    /**
     * @param Media $media
     * @param UploadService $uploadService
     *
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
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\UploadService $uploadService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeMultiple(Request $request, UploadService $uploadService): JsonResponse
    {



        $mediaFiles = (new Media)->whereIn('id', $request->input('media'))->get();


        foreach ($mediaFiles as $media) {
            try {
                $status = $uploadService->removeMedia($media);
                if (!$status) {
                    throw new \Exception('Can\'t remove the file');
                }
            } catch (\Exception $exception) {
                return new JsonResponse(['success' => false, 'message' => $exception->getMessage()]);
            }
            event(new DeletedFileEvent($media, $media->brand));

        }

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

        if (!$license) {
            $license = $licenseModelManager->createFromRequest($request);
            $media->license()->associate($license);
        }

        $media->save();

        return new JsonResponse(new TransformerEngine($media, new MediaTransformer()));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function downloadMultiple(Request $request)
    {
        $media = (new Media)->whereIn('id', $request->input('media'))->get();
        $fileName = str_random();
        $zip = new \ZipArchive();
        $path = 'zips/' . $fileName . '.zip';
        $filename = storage_path('app/brands/' . $path);
        $deleteFiles = [];

        if ($zip->open($filename, \ZipArchive::CREATE) !== true) {
            exit("cannot open <$filename>\n");
        }

        foreach ($media as $mediaItem) {
            /**
             * @var Media $mediaItem
             */
            $localFile = storage_path('app/brands/' . $mediaItem->dir . '/' . $mediaItem->file_name);
            $remoteFile = \Storage::disk('s3')->get($mediaItem->getFilePath());
            file_put_contents($localFile, $remoteFile);
            $zip->addFile($localFile, $mediaItem->origin_name);
            $deleteFiles[] = $localFile;
        }

        $zip->close();

        foreach ($deleteFiles as $deleteFile) {
            unlink($deleteFile);
        }

        return new JsonResponse(['url' => route('download', ['hash' => $fileName])]);
    }
}
