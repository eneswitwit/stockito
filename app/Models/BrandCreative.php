<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BrandCreative
 *
 * @package App\Models
 * @property int $id
 * @property int $brand_id
 * @property int $creative_id
 * @property int $position
 * @property string $role
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrandCreative whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrandCreative whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrandCreative whereCreativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrandCreative whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrandCreative wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrandCreative whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrandCreative whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BrandCreative extends  Model
{

	protected $table = 'brand_creative';

}
