<?php

namespace App\Admin\ModelConfiguration;

use App\Models\Admin;
use SleepingOwl\Admin\Display\Display;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\Column\Text as TextColumn;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Form\Element\Password;
use SleepingOwl\Admin\Form\Element\Text;
use SleepingOwl\Admin\Form\FormPanel;
use SleepingOwl\Admin\Model\ModelConfiguration;

\AdminSection::registerModel(Admin::class, function (ModelConfiguration $model) {
    $model->setTitle('Admins');
    // Display
    $model->onDisplay(function () {
        return (new DisplayDatatablesAsync())->setDatatableAttributes([
            'class' => 'table-responsive'
        ])->setColumns([
            new Link('id', '#ID'),
            new Link('name', 'Name'),
            new TextColumn('email', 'email'),
             new TextColumn('created_at', 'Created At')
        ])
            ->paginate(15);
    });

	// Create And Edit
	$model->onCreateAndEdit(function() {
		return (new FormPanel())->setItems(
			(new Text('name', 'Name'))->required(),
			(new Text('email', 'E-mail'))->required(),
			(new Password('password', 'Password'))->required()->allowEmptyValue()->hashWithBcrypt(),
			(new Password('password_confirmation', 'Confirm Password'))
				->setValueSkipped(true)
				->required()
				->allowEmptyValue()
				->addValidationRule('same:password', 'Passwords must match!')
		);
	});
});
