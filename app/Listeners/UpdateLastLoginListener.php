<?php

namespace App\Listeners;

use App\Events\AbstractActivityEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Events\Login;
use Carbon\Carbon;

class UpdateLastLoginListener
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}


	public function handle(Login $event)
	{
 		$event->user->last_login = Carbon::now();
		$event->user->save();

	}
}
