<?php

namespace App\Admin\ModelConfiguration;

use App\Models\User;
use SleepingOwl\Admin\Display\Column\Custom;
use SleepingOwl\Admin\Display\Column\DateTime;
use SleepingOwl\Admin\Display\Column\Text;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Model\ModelConfiguration;

\AdminSection::registerModel(User::class, function (ModelConfiguration $model) {
    $model->setTitle('Logins');
    // Display
    $model->disableCreating();
    $model->disableEditing();
    $model->disableDeleting();
    $model->setAlias('logins');

    $model->onDisplay(function () {
        return (new DisplayDatatablesAsync())->setColumns([
            new Custom('Name', function (User $instance) {
                return $instance->getName();
            }),
            new Custom('Type', function (User $instance) {
              return $instance->getType() ? $instance->getType()->getUserTypeName() : '';
            }),
            new Text('email', 'Email'),
            (new DateTime('last_login', 'Last Login'))->setFormat('Y-m-d H:i:s')
        ]);
    });
});
