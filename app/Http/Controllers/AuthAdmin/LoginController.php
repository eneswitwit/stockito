<?php
namespace App\Http\Controllers\AuthAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\AuthAdmin
 */
class LoginController extends Controller
{
	use AuthenticatesUsers;
	/**
	 * @var string
	 */
	protected $redirectTo = '/admin/';

	/**
	 * @var string
	 */
	protected $redirectAfterLogout = '/admin/';

	public function __construct()
	{
		$this->middleware('guest', ['except' => 'logout']);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function showLoginForm()
	{
		return view('admin.auth.login');
	}

	/**
	 * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
	 */
	protected function guard()
	{
		return Auth::guard('admin');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	protected function logout(Request $request)
	{
		$this->guard()->logout();
		$request->session()->invalidate();
		return redirect('/admin/login');
	}
}