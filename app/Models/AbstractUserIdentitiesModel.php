<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Longman\LaravelLodash\Eloquent\UserIdentities;

abstract class AbstractUserIdentitiesModel extends  Model
{
    use UserIdentities;

    /**
     * @return BelongsTo
     */
    public function createdBy () : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy () : BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @return BelongsTo
     */
    public function deletedBy () : BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}