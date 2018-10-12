<?php

// namespace
namespace App\Models;

// use
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class GDPR
 * @property int $id
 * @property int $user_id
 * @property string $stripe_plan_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @package App\Models
 */
class GDPR extends Model
{
    protected $table = 'gdpr';
}
