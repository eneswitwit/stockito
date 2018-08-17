<?php

namespace App\Admin\ModelConfiguration;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Display\Column\Custom;
use SleepingOwl\Admin\Display\Column\Filter\Date;
use SleepingOwl\Admin\Display\Column\Filter\Range;
use SleepingOwl\Admin\Display\Column\Filter\Text;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\Column\Text as TextColumn;
use SleepingOwl\Admin\Display\ControlLink;
//use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Display\DisplayDatatables;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use SleepingOwl\Admin\Display\DisplayTable;
use SleepingOwl\Admin\Display\Filter\FilterCustom;
use SleepingOwl\Admin\Display\Filter\FilterField;
use SleepingOwl\Admin\Model\ModelConfiguration;

\AdminSection::registerModel(Invoice::class, function (ModelConfiguration $model) {
    $model->setTitle('Finance & Invoices');
    // Display
    $model->onDisplay(function () {
        $table = (new DisplayDatatablesAsync())->setFilters(
            new FilterCustom('startDate', 'Date From [:value]', function (Builder $q, $value) {
                $q->where('date', '>=', $value);
            }),
            new FilterCustom('endDate', 'Date From [:value]', function (Builder $q, $value) {
                $q->where('date', '=<', $value);
            })
        )->setColumns([
            new Link('id', '#ID'),
            new Link('number', 'Number'),
            new Custom('Brand', function (Invoice $invoice) {
                if ($invoice->brand) {
                    return '<a href="'.route('admin.model.edit', ['brands', $invoice->brand]).'" class="btn btn-link">'.$invoice->brand->company_name.'</a>';
                }
                return '';
            }),
            new Custom('Amount', function (Invoice $invoice) {
                return $invoice->amount / 100 . ' ' .$invoice->currency;
            }),
            new TextColumn('date', 'Date')
        ])->paginate(10);

        $table->setColumnFilters(
            null,
            (new Text())->setPlaceholder('Number')->setOperator(FilterInterface::CONTAINS),
            null,
            (new Text())->setPlaceholder('Amount')->setOperator(FilterInterface::CONTAINS),
            (new Range())
                ->setFrom((new Date())->setPlaceholder('Date From')->setOperator(FilterInterface::GREATER_OR_EQUAL)->setFormat('Y-m-d H:i:s'))
                ->setTo((new Date())->setPlaceholder('Date To')->setOperator(FilterInterface::LESS_OR_EQUAL)->setFormat('Y-m-d H:i:s'))
        );

        $table->getColumns()->getControlColumn()->addButton((new ControlLink(function (Invoice $invoice) {
            return route('admin.invoices.show', $invoice);
        }, '', 50))->setHtmlAttribute('class', 'btn btn-primary')->setIcon('fa fa-eye'));

        return $table;
    });
});
