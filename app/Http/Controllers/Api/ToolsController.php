<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Http\Requests\Tools\ImgToBase64Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Class ToolsController
 *
 * @package App\Http\Controllers\Api
 */
class ToolsController extends Controller
{
    /**
     * @param ImgToBase64Request $request
     * @return JsonResponse
     */
    public function imgToBase64 (ImgToBase64Request $request): JsonResponse
    {
        $image = $request->file('image');
        $imageData = file_get_contents($image);
        $base64 = base64_encode($imageData);
        return new JsonResponse('data:'.$image->getMimeType().';base64,'.$base64);
    }
}
