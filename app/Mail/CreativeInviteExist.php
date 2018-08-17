<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreativeInviteExist extends Mailable
{
    use Queueable, SerializesModels;

	public $brand_name;
	public $role;

	/**
	 * Create a new message instance.
	 *
	 * @param $brand_name
	 * @param $role
	 */
	public function __construct( $brand_name, $role ) {
		$this->brand_name = $brand_name;
		$this->role       = $role;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build(): self {
		return $this->markdown( 'mails.creative.inviteExist')->subject('You are invited to creative team!');;
	}
}
