<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SystemSettings
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSettings whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSettings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSettings whereValue($value)
 */
class SystemSettings extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['key', 'value'];
}
