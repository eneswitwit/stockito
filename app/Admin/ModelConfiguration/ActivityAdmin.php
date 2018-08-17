<?php

namespace App\Admin\ModelConfiguration;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\Column\Text as TextColumn;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Form\Element\Password;
use SleepingOwl\Admin\Form\Element\Text;
use SleepingOwl\Admin\Form\FormPanel;
use SleepingOwl\Admin\Model\ModelConfiguration;

\AdminSection::registerModel(Activity::class, function (ModelConfiguration $model) {
    $model->setTitle('Uploads');
    // Display
    $model->onDisplay(function () {
        return (new DisplayDatatablesAsync())->setApply(function (Builder $activity) {
            $activity->whereIn('type', [Activity::DELETE_MEDIA_TYPE, Activity::EDIT_MEDIA_TYPE, Activity::UPLOAD_MEDIA_TYPE]);
        })->setDatatableAttributes([
            'class' => 'table-responsive'
        ])->setColumns([
            new Link('id', '#ID'),
            new TextColumn('message', ''),
            new TextColumn('created_at', '')
        ])->paginate(20);
    });
});
