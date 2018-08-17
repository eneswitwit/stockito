<?php

namespace App\Admin\ModelConfiguration;

use App\Models\BrandCreativePivotModel;
use App\Models\Creative;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\ControlLink;
use SleepingOwl\Admin\Display\DisplayDatatables;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Display\DisplayTab;
use SleepingOwl\Admin\Display\DisplayTabbed;
use SleepingOwl\Admin\Form\Element\Text;
use SleepingOwl\Admin\Form\FormElements;
use SleepingOwl\Admin\Form\FormPanel;
use SleepingOwl\Admin\Model\ModelConfiguration;

\AdminSection::registerModel(Creative::class, function (ModelConfiguration $model) {
    $model->setTitle('Creatives');
    $model->setEditTitle('Editing Creative');
    // Display
    $model->onDisplay(function () {
        return (new DisplayDatatablesAsync())->setDisplaySearch('Search')->setDatatableAttributes([
            'class' => 'table-responsive'
        ])->setColumns([
            new Link('id', '#ID'),
            new Link('first_name', 'First Name'),
            new Link('last_name', 'Last Name'),
            new Link('user.email', 'Email'),
            new Link('company', 'Company'),
            new Link('created_at', 'Registered At')
        ])->paginate(15);
    });

    // Create And Edit
    $model->onCreateAndEdit(function($id = null) {
        $fields = [
            (new Text('first_name', 'First Name'))->required(),
            (new Text('last_name', 'Last Name'))->required(),
            (new Text('user.email', 'Email'))->required(),
            (new Text('company', 'Company/Agency'))->required()
        ];

        if (null !== $id) {

            $tabs = [new DisplayTab(new FormElements($fields), 'General Info')];

            $table = (new DisplayDatatables())->setModelClass(BrandCreativePivotModel::class)->setApply(function (Builder $q) use ($id) {
                $q->where('creative_id', $id);
            })->paginate(15)->setColumns([
                new Link('brand_id', '#ID'),
                (new Link('brand.brand_name', 'Name')),
            ]);

            $control = $table->getColumns()->getControlColumn();

            $button = (new ControlLink(function (BrandCreativePivotModel $model) {
                return route('admin.model.edit', ['brands', $model->brand]);
            }, '', 50))->setIcon('fa fa-pencil')->setHtmlAttribute('class', 'btn btn-primary');

            $control->addButton($button);

            $tabs[] = new DisplayTab($table, 'Brands Involved');

            return (new FormPanel())->setItems((new DisplayTabbed())->setTabs($tabs));
        }

        return (new FormPanel())->setItems($fields);
    });
});
