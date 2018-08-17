<?php

namespace App\Admin\ModelConfiguration;

use App\Models\Plan;
use App\Models\Product;
use App\Repositories\ProductRepository;
use SleepingOwl\Admin\Display\Column\Custom;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Form\Element\Select;
use SleepingOwl\Admin\Form\Element\Text;
use SleepingOwl\Admin\Form\FormPanel;
use SleepingOwl\Admin\Model\ModelConfiguration;
use Stripe\Error\InvalidRequest;

\AdminSection::registerModel(Plan::class, function (ModelConfiguration $model) {
    $model->setTitle('Plans');

    // Display
    $model->onDisplay(function () {
        return (new DisplayDatatablesAsync())->setDatatableAttributes([
            'class' => 'table-responsive'
        ])->setColumns([
            new Link('id', '#ID'),
            new Link('stripe_id', 'Stripe ID'),
            new Link('product.name', 'Product'),
            new Custom('interval', function (Plan $plan) {
                return $plan->getIntervalTitle();
            }),
            new Custom('Price', function (Plan $plan) {
                return $plan->price . $plan->getCurrencySymbol();
            }),
        ])->paginate(15);
    });

    $model->onCreate(function () {
        return (new FormPanel())->setItems([
            (new Select('product_id', 'Product', Product::class))->setDisplay('name')->required(),
            (new Text('price', 'Annually Price ($)'))->setValidationRules('numeric')->required(),
            (new Select('interval', 'Interval'))->setOptions(Plan::getIntervalTitles())->required(),
        ]);
    });

    $model->creating(function(ModelConfiguration $model, Plan $plan) {
        if($model->isCreatable()) {
            $stripeProduct = $plan->product->getStripeProduct();
            $productRepository = new ProductRepository(new Plan());
            $stripePlan = $productRepository->createStripePlan($stripeProduct, (int)$plan->price * 100, $plan->interval);
            $plan->stripe_id = $stripePlan->id;
        }
    });

    $model->deleting(function (ModelConfiguration $modelConfiguration, Plan $plan) {
        try {
            $plan->getStripePlan()->delete();
        } catch (InvalidRequest $exception) {

        }
    });
});
