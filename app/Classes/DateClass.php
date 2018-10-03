<?php

// namespace
namespace App\Classes;

// use
use Carbon\Carbon;

/**
 * Class DateClass
 *
 * @package App\Classes
 */
class DateClass
{
    /**
     * @param Carbon $carbon
     * @return array
     */
    public static function transformCarbon(Carbon $carbon): array
    {
        return [
            'original' => $carbon,
            'Ymd' => $carbon->format('Y-m-d'),
            'YmdHis' => $carbon->format('Y-m-d H:i:s'),
            'YMd' => $carbon->format('Y M d'),
            'dMY' => $carbon->format('d M Y'),
            'difference' => $carbon->isPast() ? -1*$carbon->diffInDays(Carbon::now()) : $carbon->diffInDays(Carbon::now())
        ];
    }
}