<?php

// namespace
namespace App\Http\Controllers;

// use
use App\Models\Invoice;
use App\Models\License;
use App\Models\Media;
use App\Models\Share;
use App\Models\User;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class FileController
 *
 * @package App\Http\Controllers
 */
class FileController extends Controller
{
    /**
     * @var InvoiceService
     */
    protected $invoiceService;

    /**
     * FileController constructor.
     *
     * @param InvoiceService $invoiceService
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $name
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function image(Request $request, $name)
    {
        /**
         * @var User $user
         */
//        $user = $request->user();
        $media = (new Media())->where('file_name', 'LIKE', $name)->firstOrFail();
//        if ($user->can('showMedia', $media)) {
        return new Response($media->getFile());
//        }
//        abort(404);
    }

    /**
     * @param Request $request
     * @param $name
     *
     * @return StreamedResponse
     */
    public function imageThumbnail(Request $request, $name)
    {
        /**
         * @var User $user
         */
//        $user = $request->user();
        $media = (new Media())->where('thumbnail', 'LIKE', $name)->firstOrFail();
//        if ($user->can('showMedia', $media)) {
        return Storage::disk('brands_thumbnail')->response($media->dir . '/' . $media->thumbnail);
//        }
//        abort(404);
    }

    /**
     * @param Request $request
     * @param $name
     *
     * @return Response
     */
    public function license(Request $request, $name)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $license = (new License())->where('bill_file', 'LIKE', $name)->firstOrFail();
        if ($user->can('showLicense', $license)) {
            return $license->downloadFile();
        }
        abort(404);
    }

    /**
     * @param $hash
     *
     * @return StreamedResponse
     */
    public function downloadZip($hash): StreamedResponse
    {
        return Storage::disk('brands')->download('zips/' . $hash . '.zip', 'images.zip');
    }

    /**
     * @param Invoice $invoice
     *
     * @return Response
     * @throws \Throwable
     */
    public function downloadInvoicePdf(Invoice $invoice): Response
    {
        $pdf = $this->invoiceService->getPdf($invoice);
        return $pdf->download($invoice->getFileName());
    }

    /**
     * @param $hash
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shareImage($hash)
    {
        /**
         * @var User $user
         */
        $share = (new Share)->where('hash', 'LIKE', $hash)->active()->firstOrFail();
        $share->opened = true;
        $share->save();

        return view('share-images', ['images' => $share->medias]);
    }
}
