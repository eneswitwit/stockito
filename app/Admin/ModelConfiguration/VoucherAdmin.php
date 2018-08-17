<?php

namespace App\Admin\ModelConfiguration;

use App\Models\Plan;
use App\Models\Voucher;
use SleepingOwl\Admin\Display\Column\Custom;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Form\Element\Radio;
use SleepingOwl\Admin\Form\Element\Text;
use SleepingOwl\Admin\Form\FormPanel;
use SleepingOwl\Admin\Model\ModelConfiguration;
use Stripe\Coupon;
use Stripe\Error\InvalidRequest;

\AdminSection::registerModel(Voucher::class, function (ModelConfiguration $model) {
    $model->setTitle('Vouchers');

    // Display
    $model->onDisplay(function () {
        return (new DisplayDatatablesAsync())->setDatatableAttributes([
            'class' => 'table-responsive'
        ])->setColumns([
            new Link('id', '#ID'),
            new Link('code', 'Code'),
            new Custom('Price Reduction', function (Voucher $voucher) {
                return $voucher->type === Voucher::PERCENTAGE_REDUCTION ? $voucher->amount.'%' : $voucher->amount.'$';
            }),
        ])->paginate(15);
    });

    $model->onCreate(function () {
        return (new FormPanel())->setItems([
            (new Radio('type', 'Type'))->setOptions(Voucher::$typesReductionTitled)->setDefaultValue(Voucher::AMOUNT_REDUCTION),
            (new Text('amount', 'Amount'))->setHelpText('$ or % price reduction')->required(),
            (new Text('code', 'Code'))->setDefaultValue(str_random())->required(),
        ]);
    });

    $model->creating(function(ModelConfiguration $model, Voucher $voucher) {
        try {
            $couponParams = [
                'duration' => 'forever',
                'currency' => Plan::CURRENCY_USD,
                'duration_in_months' => null,
                'max_redemptions' => null,
                'id' => $voucher->code
            ];

            switch ($voucher->type) {
                case Voucher::AMOUNT_REDUCTION:
                    $couponParams['amount_off'] = $voucher->amount * 100;
                    break;
                case Voucher::PERCENTAGE_REDUCTION:
                    $couponParams['percent_off'] = $voucher->amount;
                    break;
                default:
                    $couponParams['amount_off'] = $voucher->amount * 100;
                    break;
            }

            Coupon::create($couponParams);
        } catch (\Exception $exception) {
            return false;
        }
    });

    $model->deleting(function(ModelConfiguration $model, Voucher $voucher) {
        try {
            $voucher->getStripeCoupon()->delete();
        } catch (InvalidRequest $exception) {}
    });
});
