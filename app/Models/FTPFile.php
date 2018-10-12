<?php

// namesapce
namespace App\Models;

// use
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\FTPFile
 *
 * @property int $id
 * @property string $file
 * @property int $size
 * @property string $username
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $handled
 * @property string|null $handled_at
 * @property-read \App\Models\FTPUser $ftpUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereHandled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereHandledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereUsername($value)
 * @mixin \Eloquent
 * @property int|null $media_id
 * @property-read \App\Models\Media|null $media
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile notHandled()
 * @property int $processing
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereProcessing($value)
 * @property int $queuing
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPFile whereQueuing($value)
 */
class FTPFile extends Model
{
    protected $table = 'ftp_files';

    /**
     * @return BelongsTo
     */
    public function ftpUser(): BelongsTo
    {
        return $this->belongsTo(FTPUser::class, 'username', 'userid');
    }

    /**
     * @return BelongsTo
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeNotHandled(Builder $builder): Builder
    {
        return $builder->where('handled', false);
    }

}
