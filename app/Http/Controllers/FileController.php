<?php

// namespace
namespace App\Http\Controllers;

// use
use App\Models\Invoice;
use App\Models\UsageLicense;
use App\Models\Media;
use App\Models\Share;
use App\Models\User;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use PDF;
use Log;
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
        $license = (new UsageLicense())->where('bill_file', 'LIKE', $name)->firstOrFail();
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
        /*$pdf = $this->invoiceService->getPdf($invoice);
        return $pdf->download($invoice->getFileName());*/

        $html = $this->invoiceService->getView($invoice);

        $snappy = \App::make('snappy.pdf');
        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'page-size' => 'A4',
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $invoice->getFileName() . '"'
            )
        );
    }


    /**
     * @param $hash
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function shareImage($hash)
    {
        /**
         * @var User $user
         */
        $share = (new Share)->where('hash', 'LIKE', $hash)->active()->firstOrFail();
        $share->opened = true;
        $share->save();

        $fileName = $hash;
        $zip = new \ZipArchive();
        $path = 'zips/' . $fileName . '.zip';
        $filename = storage_path('app/brands/' . $path);
        $deleteFiles = [];
        if (!file_exists($filename)) {
            if ($zip->open($filename, \ZipArchive::CREATE) !== true) {
                exit("cannot open <$filename>\n");
            }
            foreach ($share->medias as $mediaItem) {
                /**
                 * @var Media $mediaItem
                 */
                $localFile = storage_path('app/brands/' . $mediaItem->dir . '/' . $mediaItem->file_name);
                $remoteFile = \Storage::disk('s3')->get($mediaItem->getFilePath());
                file_put_contents($localFile, $remoteFile);
                $zip->addFile($localFile, $mediaItem->file_name);
                $deleteFiles[] = $localFile;
            }
            $zip->close();
            foreach ($deleteFiles as $deleteFile) {
                unlink($deleteFile);
            }
        }
        return \Storage::disk('brands')->download('zips/' . $hash . '.zip', 'images.zip');
    }
}
