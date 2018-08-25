<?php

namespace App\Http\Controllers\Auth;

use App\Models\Brand;
use App\Models\User;
use App\Services\FTPService;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use LukeVear\LaravelTransformer\TransformerEngine;

class RegisterController extends Controller {
	use RegistersUsers;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'guest' );
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function registerCreative( Request $request ) {
		$this->validatorCreative( $request->all() )->validate();
        $user = $this->create( $request->only( 'email', 'password' ) );

        $confirmationToken = md5(time());
        User::where('id', $user->id)->update(['confirmation_token' => $confirmationToken]);

        try {
            $user->sendConfirmationEmail($confirmationToken);
        } catch (\Exception $e) {
            $user->delete();
            return new JsonResponse(['error'=> true]);
            throw new \Exception($e);
        }

		event( new Registered( $user ) );
		$user->creative()->create( $request->only( 'first_name', 'last_name', 'company' ) );

		$confirmationToken = md5(time());
		User::where('id', $user->id)->update(['confirmation_token' => $confirmationToken]);
		$user->sendConfirmationEmail($confirmationToken);

		if ( $request->input( 'invite_token', false ) ) {
			$data = json_decode( decrypt( $request->invite_token ), true );
			if ( isset( $data['brand_id'], $data['role'], $data['position'] ) && $brand = Brand::find( $data['brand_id'] ) ) {
				$brand->creatives()->attach( $user->creative, [
					'role'     => $data['role'],
					'position' => $data['position']
				] );
			}
		}

		$this->guard()->login( $user );

		return $this->registered( $request, $user )
			?: redirect( $this->redirectPath() );
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Exception
	 */
	public function registerBrand( Request $request, FTPService $FTPService) {
	    $data = $request->all();

		$this->validatorBrand( $data )->validate();
        $user = $this->create( $request->only( 'email', 'password' ) );

        $confirmationToken = md5(time());
        User::where('id', $user->id)->update(['confirmation_token' => $confirmationToken]);

        try {
            $user->sendConfirmationEmail($confirmationToken);
        } catch (\Exception $e) {
            $user->delete();
            return new JsonResponse(['error'=> true]);
            throw new \Exception($e);
        }

        event( new Registered( $user ) );

        /**
         * @var Brand $brand
         */
        $brand = $user->brand()->create( $request->except( 'email', 'password' ) );

        $brand->makeHomeDir();
        $ftpUser = FTPService::makeFTPUserForBrand($brand);
        $ftpUser->save();

        $brand->ftpUser()->associate($ftpUser);
        $brand->save();

		$this->guard()->login( $user );

		return $this->registered( $request, $user )
			?: redirect( $this->redirectPath() );
	}

	/**
	 * The user has been registered.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  mixed $user
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function registered( Request $request, $user ) {
		return new JsonResponse( new TransformerEngine( $user, new UserTransformer() ) );
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator( array $data ) {
		return Validator::make( $data, [
			'email'    => 'required|email|max:255|unique:users',
			'password' => 'required|min:6',
		] );
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validatorCreative( array $data ) {
		return Validator::make( $data, [
			'email'      => 'required|email|max:255|unique:users',
			'password'   => 'required|min:6|confirmed',
			'first_name' => 'required|min:2',
			'last_name'  => 'required|min:2',
			'company'    => 'required|min:2',
		] );
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validatorBrand( array $data ) {
		return Validator::make( $data, [
			'email'              => 'required|email|max:255|unique:users',
			'password'           => 'required|min:6',
			'company_name'       => 'required',
			'brand_name'         => 'required',
			'address_1'          => 'required',
			'address_2'          => 'required',
			'city'               => 'required',
			'country_id'         => 'required',
			'zip'                => 'required',
			'eur_uid'            => 'required',
			'homepage'           => 'required',
			'phone'              => 'required',
			'contact_first_name' => 'required',
			'contact_last_name'  => 'required',
			'contact_title'      => 'required',
			'plan_id'            => 'required|exists:plans,id'
		], [], [
			'zip'        => 'ZIP Code',
			'eur_uid'    => 'European UID Number',
			'country_id' => 'Country',
			'plan_id'    => 'Subscription Plan',
		] );
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 *
	 * @return User
	 */
	protected function create( array $data ) {
		return User::create( [
			'email'    => $data['email'],
			'password' => bcrypt( $data['password'] ),
		] );
	}

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function confirmEmail(Request $request) {
        $confirmationToken = $request->confirmationToken;

        $user = User::where('confirmation_token', $confirmationToken);

        if ($user->exists()) {
            $user->update(['enabled' => 1, 'confirmation_token' => null]);
            return new JsonResponse(true);
        }

        return new JsonResponse(false);
    }
}