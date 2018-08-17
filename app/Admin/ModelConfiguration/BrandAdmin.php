<?php

namespace App\Admin\ModelConfiguration;

use App\Models\Brand;
use App\Models\BrandCreativePivotModel;
use App\Models\Country;
use App\Models\Invoice;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Display\Column\Custom;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\ControlLink;
use SleepingOwl\Admin\Display\DisplayDatatables;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Display\DisplayTab;
use SleepingOwl\Admin\Display\DisplayTabbed;
use SleepingOwl\Admin\Form\Element\Select;
use SleepingOwl\Admin\Form\Element\Text;
use SleepingOwl\Admin\Form\Element\View;
use SleepingOwl\Admin\Form\FormElements;
use SleepingOwl\Admin\Form\FormPanel;
use SleepingOwl\Admin\Model\ModelConfiguration;

\AdminSection::registerModel(Brand::class, function (ModelConfiguration $model) {
    $model->setTitle('Brands');
    // Display
    $model->onDisplay(function () {
        return (new DisplayDatatablesAsync())->setDisplaySearch('Search')->setDatatableAttributes([
            'class' => 'table-responsive'
        ])->setColumns([
            new Link('id', '#ID'),
            new Link('brand_name', 'Brandname'),
            new Custom('Product', function (Brand $brand) {
                $brandName = $brand->getProduct()->name ?? '';
                $route = $brand->getProduct() ? route('admin.model.edit', ['products', $brand->getProduct()->id]) : '';
                return "<a href='$route'>$brandName</a>";
            }),

            new Custom('Subscription Exp. Date', function (Brand $brand) {
                return $brand->user->subscription()->ends_at ?? 'Auto-renewal';
            }),
            new Custom('Used Storage', function (Brand $brand) {
                return UploadService::formatedUsedStorage($brand)['usedFormated'];
            }),
            new Link('created_at', 'Registered At')
        ])->paginate(15);
    });

    // Create And Edit
    $model->onEdit(function($id = null) {
        $table = (new DisplayDatatables())->setModelClass(BrandCreativePivotModel::class)->setApply(function (Builder $q) use ($id) {
            $q->where('brand_id', $id);
        })->paginate(15)->setColumns([
            new Link('creative_id', '#ID'),
            new Link('creative.name', 'Name'),
        ]);

        $invoicesTable = (new DisplayDatatables())->setModelClass(Invoice::class)->setApply(function (Builder $q) use ($id) {
            $q->where('brand_id', $id);
        })->paginate(15)->setColumns([
            new Link('number', 'Number'),
            new Custom('Amount', function (Invoice $invoice) {
                return $invoice->amount / 100 . ' ' . $invoice->currency;
            }),
        ]);

        $invoicesTable->getColumns()->getControlColumn()->addButton((new ControlLink(function (Invoice $invoice) {
            return route('admin.invoices.show', [$invoice]);
        }, 'Show', 50))->setHtmlAttribute('class', 'btn btn-primary'));


        $table->getColumns()->getControlColumn()->addButton((new ControlLink(function (BrandCreativePivotModel $model) {
            return route('admin.model.edit', ['creatives', $model->creative]);
        }, '', 50))->setIcon('fa fa-pencil')->setHtmlAttribute('class', 'btn btn-primary'));

        $brand = Brand::find($id);

        $view = new View('admin.brand.usage.storage', ['sData' => UploadService::formatedUsedStorage($brand)]);

        $tabs = [
            new DisplayTab(new FormElements([
                (new Text('brand_name', 'Brand Name'))->required(),
                (new Text('user.email', 'E-mail'))->required(),
            ]), 'General Info'),
            new DisplayTab(new FormElements([
                (new Text('company_name', 'Company Name'))->required(),
                (new Text('address_1', 'Address 1'))->required(),
                (new Text('address_2', 'Address 2'))->required(),
                (new Select('country_id', 'Country', Country::class))->setDisplay('name')->required(),
                (new Text('city', 'City'))->required(),
                (new Text('zip', 'Zip'))->required(),
                (new Text('eur_uid', 'EUR UID'))->required(),
                (new Text('homepage', 'Home Page'))->required(),
                (new Text('phone', 'Phone'))->required(),
                (new Text('contact_first_name', 'Contact First Name'))->required(),
                (new Text('contact_last_name', 'Contact Last Name'))->required(),
                (new Text('contact_title', 'Contact Title'))->required(),
            ]), 'Billing Address'),
            new DisplayTab($table, 'Connected Creatives', '<span class="fa fa-group"></span>'),
            new DisplayTab($view, 'Usage'),
            new DisplayTab($invoicesTable, 'Invoices')
        ];

        if ($brand->user->subscribed('main')) {
            $subscriptionView = new View('admin.brand.subscription', ['brand' => $brand, 'product' => $brand->getProduct()]);
            $tabs[] = new DisplayTab($subscriptionView, 'Subscription');
        }

        return (new FormPanel())->setItems((new DisplayTabbed())->setTabs($tabs));
    });
});
