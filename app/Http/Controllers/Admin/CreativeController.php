<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Session;
use AdminSection;
use App\Models\User;
use App\Models\BrandCreative;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Creative;
use Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Services\SubscriptionsService;

/**
 * Class CreativeController
 *
 * @package App\Http\Controllers\Admin
 */
class CreativeController extends  Controller
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return AdminSection::view(view('admin.creative.index'));
	}

	/**
	 * Process datatables ajax request.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function CreativesData() : JsonResponse
	{
		$creatives = Creative::select();
		return Datatables::of($creatives)
			->addColumn('email', function (Creative $creative) {
				return $creative->user->email;
			})
			->make(true);
	}

	/**
	 * @param Creative $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function CreativeDetail(Creative $id)
	{
		$creative = Creative::find($id->id);
		return AdminSection::view(view('admin.creative.detail', [
			'creative' => $creative,
		]), 'Creative Details');
	}

	/**
	 * @param Creative $id
	 * @param Request  $request
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function CreativeEdit(Creative $id, Request $request)
	{
		$creative = Creative::find($id->id);

 		$validator = Validator::make($request->all(), [
			'first_name' => 'required',
			'email' => 'required|email|unique:users,email,'.$creative->user_id,
			'last_name' =>  'required',
			'company' =>  'required',
		]);

		if ($validator->fails()) {
			return redirect()->route('admin.creative.edit',['id'=>$id->id])
				->withErrors($validator);
		} else {
			// store
			$creative->first_name 	= $request->input('first_name');
			$creative->last_name = $request->input('last_name');
			$creative->company = $request->input('company');

			$creative->save();

			$BrandUser = User::find($creative->user_id);
			$BrandUser->email = $request->input('email');
			$BrandUser->save();

			// redirect
			Session::flash('message', 'Successfully updated Creative!');
			return redirect()->route('admin.creative.edit',['id'=>$id->id]);
		}
	}


	/**
	 * @param Creative $id
	 * @param Brand    $brand_id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function RemoveBrand(Creative $id, Brand $brand_id)
	{
		$BrandCreative = BrandCreative::where([
			['brand_id', '=', $brand_id->id],
			['creative_id', '=', $id->id]
		])->firstOrFail()->delete();

		Session::flash('message', 'Successfully brand was remove from  Creative!');
		return redirect()->route('admin.creative.edit',['id'=>$id->id]);
	}
}