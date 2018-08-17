<?php

use SleepingOwl\Admin\Navigation\Page;

// Default check access logic
// AdminNavigation::setAccessLogic(function(Page $page) {
// 	   return auth()->user()->isSuperAdmin();
// });
//
// AdminNavigation::addPage(\App\Models\User::class)->setTitle('test')->setPages(function(Page $page) {
// 	  $page
//		  ->addPage()
//	  	  ->setTitle('Dashboard')
//		  ->setUrl(route('admin.dashboard'))
//		  ->setPriority(100);
//
//	  $page->addPage(\App\Models\User::class);
// });
//
// // or
//
// AdminSection::addMenuPage(\App\Models\User::class)

return [
    [
        'title' => 'Dashboard',
        'icon'  => 'fa fa-dashboard',
        'url'   => route('admin.dashboard'),
    ],

    [
        'title' => 'Customers',
        'icon'  => 'fa fa-group',
        'pages' => [
            new Page(\App\Models\Creative::class),
            new Page(\App\Models\Brand::class),
        ]
    ],

    (new Page(\App\Models\Invoice::class))->setIcon('fa fa-credit-card'),

    [
        'title' => 'Products',
        'icon'  => 'fa fa-newspaper-o',
        'pages' => [
            new Page(\App\Models\Product::class),
            new Page(\App\Models\Plan::class),
            new Page(\App\Models\Voucher::class),
        ]
    ],

    (new Page(\App\Models\Media::class))->setIcon('fa fa-image')->setTitle('Medias'),

    (new Page(\App\Models\Admin::class))->setIcon('fa fa-users'),

    (new Page(\App\Models\User::class))->setIcon('fa fa-sign-in')->setTitle('Logins'),
    (new Page(\App\Models\Activity::class))->setIcon('fa fa-adn')->setTitle('Activities'),

    // Examples
    // [
    //    'title' => 'Content',
    //    'pages' => [
    //
    //        \App\Models\User::class,
    //
    //        // or
    //
    //        (new Page(\App\Models\User::class))
    //            ->setPriority(100)
    //            ->setIcon('fa fa-user')
    //            ->setUrl('users')
    //            ->setAccessLogic(function (Page $page) {
    //                return auth()->user()->isSuperAdmin();
    //            }),
    //
    //        // or
    //
    //        new Page([
    //            'title'    => 'News',
    //            'priority' => 200,
    //            'model'    => \App\News::class
    //        ]),
    //
    //        // or
    //        (new Page(/* ... */))->setPages(function (Page $page) {
    //            $page->addPage([
    //                'title'    => 'Blog',
    //                'priority' => 100,
    //                'model'    => \App\Blog::class
	//		      ));
    //
	//		      $page->addPage(\App\Blog::class);
    //	      }),
    //
    //        // or
    //
    //        [
    //            'title'       => 'News',
    //            'priority'    => 300,
    //            'accessLogic' => function ($page) {
    //                return $page->isActive();
    //		      },
    //            'pages'       => [
    //
    //                // ...
    //
    //            ]
    //        ]
    //    ]
    // ]
];