<?php

namespace App\ViewComposers;


use Illuminate\View\View;

class AdminProfileViewComposer
{
	/**
	 * @param View $view
	 */
	public function compose(View $view)
	{
		$view->with('user', \Auth::guard('admin')->user());
	}
}
