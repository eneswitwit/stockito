<?php

namespace App\Http\Controllers\Admin;

use AdminSection;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Brand;
use Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Services\SubscriptionsService;
use  \SleepingOwl\Admin\Form\FormPanel;
use Session;

/**
 * Class BrandController
 *
 * @package App\Http\Controllers\Admin
 */
class BrandController extends  Controller
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return AdminSection::view(view('admin.brand.index'));
	}

	/**
	 * Process datatables ajax request.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function BrandsData() : JsonResponse
	{
		return Datatables::of(Brand::query())->make(true);
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View\
	 */
	public function BrandsDetail($id)
	{
		$Brand = Brand::findOrFail($id);
		$BrandUser = User::findOrFail($Brand->user_id);
		$Countries = Country::all();

		return AdminSection::view(view('admin.brand.detail', [
			'Brand' => $Brand,
			'BrandUser' => $BrandUser,
			'Countries' => $Countries
			]), 'Brand Details');
	}

	/**
	 * @param         $id
	 * @param Request $request
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function  BrandsEdit($id, Request $request)
	{
		$Brand = Brand::find($id);

		$validator = Validator::make($request->all(), [
 			'brand_name' => 'required',
			'email' => 'required|email|unique:users,email,'.$Brand->user_id,
			'company_name' =>  'required',
			'address_1' =>  'required',
			'address_2' =>  'required',
			'city' =>  'required',
			'zip' => 'required|numeric',
			'country_id' => 'required|numeric',
			'homepage' => 'required|url',
			'phone' =>  'required',
			'contact_first_name' =>  'required',
			'contact_last_name' =>  'required',
			'contact_title' =>  'required'
		]);


		if ($validator->fails()) {
			return redirect()->route('admin.brand.edit',['id'=>$id])
				->withErrors($validator);
		} else {
			// store
			$Brand->brand_name 	= $request->input('brand_name');
			$Brand->company_name = $request->input('company_name');
			$Brand->address_1 = $request->input('address_1');
			$Brand->city = $request->input('city');
			$Brand->zip = $request->input('zip');
			$Brand->country_id = $request->input('country_id');
			$Brand->homepage = $request->input('homepage');
			$Brand->phone = $request->input('phone');
			$Brand->contact_first_name = $request->input('contact_first_name');
			$Brand->contact_last_name = $request->input('contact_last_name');
			$Brand->contact_title = $request->input('contact_title');
			$Brand->save();

			$BrandUser = User::find($Brand->user_id);
			$BrandUser->email = $request->input('email');
			$BrandUser->save();

			// redirect
			Session::flash('message', 'Successfully updated Brand!');
			return redirect()->route('admin.brand.edit',['id'=>$id]);
		}
	}
}