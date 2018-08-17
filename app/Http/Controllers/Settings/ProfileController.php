<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use LukeVear\LaravelTransformer\TransformerEngine;
use App\Transformers\UserTransformer;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        return tap($user)->update($request->only('name', 'email'));
    }

    /**
     * Update the creatives profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateCreative(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'creative.first_name' => 'required|min:2',
            'creative.last_name' => 'required|min:2',
            'creative.company' => 'required|min:2',
        ]);

        tap($user)->update($request->only('email'));
        $user->creative()->update($request->only('creative.first_name', 'creative.last_name', 'creative.company')['creative']);

        return new JsonResponse(new TransformerEngine($user, new UserTransformer()));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCreative(Request $request)
    {
        $request->user()->delete();
        return response()->json([
            'errors' => false
        ]);
    }

    /**
     * @param UpdateBrandRequest $request
     * @return JsonResponse
     */
    public function updateBrand (UpdateBrandRequest $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $brand = $user->brand;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $statusOrPath = $file->store(Brand::getLogoPath());
            if ($statusOrPath) {
                $brand->logo = $statusOrPath;
            }
        }

        $brand->fill($request->except('email', 'logo'));
        $brand->save();

        $user->email = $request->input('email');
        $user->save();

        $user = auth()->user();
        return new JsonResponse(new TransformerEngine($user, new UserTransformer()));
    }
}
