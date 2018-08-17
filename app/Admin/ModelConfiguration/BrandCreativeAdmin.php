<?php

namespace App\Admin\ModelConfiguration;

use App\Models\BrandCreativePivotModel;
use SleepingOwl\Admin\Model\ModelConfiguration;

\AdminSection::registerModel(BrandCreativePivotModel::class, function (ModelConfiguration $model) {
    $model->setTitle('BrandCreative');
    $model->setEditTitle('Editing Creative');
});
