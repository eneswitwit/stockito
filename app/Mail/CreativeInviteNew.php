<?php

namespace App\Mail;

use App\Models\Brand;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class CreativeInviteNew extends Mailable {
	use Queueable, SerializesModels;

	public $brand_name;
	public $role;
	public $url;

	/**
	 * Create a new message instance.
	 *
	 * @param Brand $brand
	 * @param $role
	 * @param $position
	 */
	public function __construct( Brand $brand, $role, $position ) {
		$invite_token     = json_encode( [
			'brand_id' => $brand->id,
			'role'     => $role,
			'position' => $position
		] );
		$this->brand_name = $brand->brand_name;
		$this->role       = $role;
		$this->url        = url( 'register/creative' ) . '?invite_token=' . encrypt( $invite_token );
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build(): self {
		return $this->markdown( 'mails.creative.inviteNew' )->subject( 'You are invited to Stockito creative team!' );
	}
}
