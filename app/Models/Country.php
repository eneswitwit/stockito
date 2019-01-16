<?php

// namespace
namespace App\Models;

// use
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country
 *
 * @property int $id
 * @property string|null $capital
 * @property string|null $citizenship
 * @property string $country_code
 * @property string|null $currency
 * @property string|null $currency_code
 * @property string|null $currency_sub_unit
 * @property string|null $currency_symbol
 * @property int|null $currency_decimals
 * @property string|null $full_name
 * @property string $iso_3166_2
 * @property string $iso_3166_3
 * @property string $name
 * @property string $region_code
 * @property string $sub_region_code
 * @property int $eea
 * @property string|null $calling_code
 * @property string|null $flag
 * @method static Builder|Country whereCallingCode($value)
 * @method static Builder|Country whereCapital($value)
 * @method static Builder|Country whereCitizenship($value)
 * @method static Builder|Country whereCountryCode($value)
 * @method static Builder|Country whereCurrency($value)
 * @method static Builder|Country whereCurrencyCode($value)
 * @method static Builder|Country whereCurrencyDecimals($value)
 * @method static Builder|Country whereCurrencySubUnit($value)
 * @method static Builder|Country whereCurrencySymbol($value)
 * @method static Builder|Country whereEea($value)
 * @method static Builder|Country whereFlag($value)
 * @method static Builder|Country whereFullName($value)
 * @method static Builder|Country whereId($value)
 * @method static Builder|Country whereIso31662($value)
 * @method static Builder|Country whereIso31663($value)
 * @method static Builder|Country whereName($value)
 * @method static Builder|Country whereRegionCode($value)
 * @method static Builder|Country whereSubRegionCode($value)
 * @mixin \Eloquent
 */
class Country extends Model
{
    //
}
