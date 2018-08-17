<?php

namespace App\Admin\ModelConfiguration;

use App\Models\Plan;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Display\Column\Action;
use SleepingOwl\Admin\Display\Column\Checkbox;
use SleepingOwl\Admin\Display\Column\Count;
use SleepingOwl\Admin\Display\Column\Custom;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\DisplayDatatables;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Display\DisplayTab;
use SleepingOwl\Admin\Display\DisplayTabbed;
use SleepingOwl\Admin\Display\Extension\Actions;
use SleepingOwl\Admin\Form\Element\Select;
use SleepingOwl\Admin\Form\Element\Text;
use SleepingOwl\Admin\Form\FormPanel;
use SleepingOwl\Admin\Model\ModelConfiguration;
use Stripe\Error\InvalidRequest;

\AdminSection::registerModel(Product::class, function (ModelConfiguration $model) {
    $model->setTitle('Products');

    $model->onDisplay(function () {
        return (new DisplayDatatablesAsync())->setDatatableAttributes([
            'class' => 'table-responsive'
        ])->setColumns([
            new Checkbox(),
            new Link('id', '#ID'),
            new Link('stripe_id', 'Stripe ID'),
            new Link('name', 'Name'),
            (new Count('plans', 'Plans'))->setOrderable(false),
            new Custom('Storage', function (Product $product) {
                return UploadService::getUnitsOfBytes($product->storage);
            })
        ])->paginate(15)->setNewEntryButtonText('New Product');
    });

    $model->onCreate(function () {
        return (new FormPanel())->setItems(
            (new Text('name', 'Name'))->required(),
            (new Text('storage', 'Storage'))->setHelpText('In bytes (Byte)')->required(),
            (new Select('product_for_update_id', 'Product for update', Product::class))->setDisplay('name')->nullable()
        );
    });

    $model->onEdit(function ($id = null) {
        $form = (new FormPanel())->setItems(
            (new Text('name', 'Name'))->setHelpText('The product name does not change in Stripe')->required(),
            (new Text('stripe_id', 'Stripe ID'))->setReadonly(true)->required(),
            (new Text('storage', 'Storage'))->setHelpText('In Megabytes (Mb)')->required(),
            (new Select('product_for_update_id', 'Product for update', Product::class))->setDisplay('name')->nullable()
        );

        $table = (new DisplayDatatables())->setModelClass(Plan::class)->setApply(function (Builder $builder) use ($id) {
            $builder->where('product_id', $id);
        })->setColumns([
            new Link('id', '#ID'),
            new Link('stripe_id', 'Stripe ID'),
            new Custom('Interval', function (Plan $plan) {
                return $plan->getIntervalTitle();
            }),
            new Custom('price', function (Plan $plan) {
                return $plan->price.$plan->getCurrencySymbol();
            }),
        ]);

        return (new DisplayTabbed())->setTabs([
            new DisplayTab($form, 'General Info'),
            new DisplayTab($table, 'Plans')
        ]);
    });

    $model->creating(function (ModelConfiguration $modelConfiguration, Product $product) {
        $productRepository = new ProductRepository();
        $stripeProduct = $productRepository->createStripeProduct($product->name, $product->storage);
        if (!$stripeProduct) {
            return false;
        }
        $product->stripe_id = $stripeProduct->id;
    });

    $model->updating(function (ModelConfiguration $modelConfiguration, Product $product) {
        $stripeProduct = $product->getStripeProduct();
        $stripeProduct->metadata['storage'] = $product->storage;
        $stripeProduct->save();
        $quotaLimit = $product->ftpGroup->getQuotaLimit();
        if ($quotaLimit) {
            $quotaLimit->bytes_out_avail = $product->storage;
            $quotaLimit->save();
        }
    });

    $model->created(function (ModelConfiguration $modelConfiguration, Product $product) {
        $productRepository = new ProductRepository();
        $productRepository->createQuotaLimits($product);
    });

    $model->deleting(function (ModelConfiguration $modelConfiguration, Product $product) {
        foreach ($product->plans as $plan) {
            try {
                $plan->getStripePlan()->delete();
            } catch (InvalidRequest $exception) {

            }
            $plan->delete();
        }
        $product->getStripeProduct()->delete();
    });
});
