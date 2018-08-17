<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class AbstractUserType extends Model implements BaseUserInterface
{
    /**
     * @return BelongsTo
     */
    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}