<?php

namespace App\Providers;

use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [

      //  \App\Models\Admin::class => 'App\Admin\Sections\UsersAdmins',
	//	\App\Models\Admin::class => 'App\Admin\Sections\CastomerBrands',
    ];

	protected $widgets = [
		\App\Admin\Widgets\ProfileWidget::class,
	];


	/**
     * Register sections.
     *
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {

        parent::boot($admin);
		$this->registerWidgets();
    }

	protected function registerWidgets ()
	{
		/** @var WidgetsRegistryInterface $widgetsRegistry */
		$widgetsRegistry = $this->app[\SleepingOwl\Admin\Contracts\Widgets\WidgetsRegistryInterface::class];

		foreach ($this->widgets as $widget) {
			$widgetsRegistry->registerWidget($widget);
		}
	}

}
