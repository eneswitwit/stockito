<?php

namespace App\Admin\Widgets;

use SleepingOwl\Admin\Widgets\Widget;

class ProfileWidget extends Widget
{

	/**
	 * @return string
	 * @throws \Throwable
	 */
	public function toHtml() : string
	{
		return view('admin.widgets.profile-widget')->render();
	}

	/**
	 * @return array|string
	 */
	public function template()
	{
		// AdminTemplate::getViewPath('dashboard') == 'sleepingowl:default.dashboard'
		return \AdminTemplate::getViewPath('_partials.header');
	}

	/**
	 * @return string
	 */
	public function block() : string
	{
		return 'navbar';
	}
}
