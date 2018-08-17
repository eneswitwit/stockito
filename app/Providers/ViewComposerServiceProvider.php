<?php

namespace App\Providers;

use App\ViewComposers\AdminProfileViewComposer;
use App\ViewComposers\GamersGearViewComposer;
use App\ViewComposers\HighlightsViewComposer;
use App\ViewComposers\LatestNewsViewComposer;
use App\ViewComposers\NewsCategoriesViewComposer;
use App\ViewComposers\TagsViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		View::composer(['admin.widgets.profile-widget'], AdminProfileViewComposer::class);
	}
}
