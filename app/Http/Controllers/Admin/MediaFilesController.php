<?php

// namespace
namespace App\Http\Controllers\Admin;

// use
use AdminSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;


/**
 * Class CreativeController
 *
 * @package App\Http\Controllers\Admin
 */
class MediaFilesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return AdminSection::view(view('admin.media_files.index'));
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function MediasData(): JsonResponse
    {
        $media = Media::select();

        return Datatables::of($media)
            ->addColumn('tags', function (Media $media) {
                return $media->tags->toArray();
            })->addColumn('brand', function (Media $media) {
                return $media->brand->name;
            })->addColumn('uploaded_by', function (Media $media) {
                return $media->brand->name;
            })
            ->make(true);

    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return mixed
     */
    public function image(Request $request, $id)
    {
        $media = Media::find($id);

        return Storage::disk('brands')->download($media->dir . '/' . $media->file_name);
    }
}