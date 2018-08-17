<?php

namespace App\Admin\ModelConfiguration;

use App\Managers\MediaManager;
use App\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Display\Column\Custom;
use SleepingOwl\Admin\Display\ControlButton;
use SleepingOwl\Admin\Display\Column\Text as TextColumn;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Model\ModelConfiguration;

\AdminSection::registerModel(Media::class, function (ModelConfiguration $model) {
    $model->setTitle('Medias');
    $model->onDisplay(function () {
        $display = (new DisplayDatatablesAsync())->setDatatableAttributes([
            'class' => 'table-responsive'
        ])->setColumns([
            new TextColumn('id', 'ID'),
            new Custom('Image', function (Media $media) {
                $thumbnail = MediaManager::getThumbnailUrl($media);
                $src = MediaManager::getMediaUrl($media);

                return "<a href='$src' data-toggle='lightbox'>
                        <img class='thumbnail' src='$thumbnail' width='80px'>
                    </a>";
            }),
            new TextColumn('origin_name', 'File Name'),
            new TextColumn('brand.brand_name', 'Brand Name'),
            new TextColumn('keywords', 'Tags'),
            new Custom('Uploaded By', function (Media $media) {
                $user = $media->createdBy;
                if (!$user) {
                    return '';
                }
                if ($brand = $user->brand) {
                    return '<a href="' . route('admin.model.edit',
                            ['brands', $brand->id]) . '">' . $brand->name . '</a>';
                }
                return '<a href="' . route('admin.model.edit',
                        ['creatives', $user->creative->id]) . '">' . $user->creative->name . '</a>';
            }),
            new TextColumn('created_at', 'Uploaded At'),
        ])->setApply(function (Builder $query) {
            $query->orderBy('created_at', 'desc');
        });

        $button = new ControlButton(function (Media $media) {
            return route('admin.media.download', ['id' => $media->id]);
        }, '');
        $button->setIcon('fa fa-arrow-circle-down');
        $button->setHtmlAttribute('class', 'btn btn-small btn-success mg-bottom-10');
        $button->setHtmlAttribute('data-toggle', 'tooltip');
        $button->setHtmlAttribute('data-original-title', 'Download');
        $display->getColumns()->getControlColumn()->addButton($button);

        return $display;
    });
});
