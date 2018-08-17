<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FTPQuotaLimits
 *
 * @property int $id
 * @property string|null $name
 * @property string $quota_type
 * @property string $per_session
 * @property string $limit_type
 * @property int $bytes_in_avail
 * @property int $bytes_out_avail
 * @property int $bytes_xfer_avail
 * @property int $files_in_avail
 * @property int $files_out_avail
 * @property int $files_xfer_avail
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereBytesInAvail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereBytesOutAvail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereBytesXferAvail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereFilesInAvail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereFilesOutAvail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereFilesXferAvail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereLimitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits wherePerSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereQuotaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPQuotaLimits whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FTPQuotaLimits extends Model
{
    public const USER_QUOTA_TYPE = 'user';
    public const GROUP_QUOTA_TYPE = 'group';
    public const CLASS_QUOTA_TYPE = 'class';
    public const ALL_QUOTA_TYPE = 'class';

    public const HARD_TYPE = 'hard';
    public const SOFT_TYPE = 'soft';

    /**
     * @var array
     */
    protected $fillable = ['name', 'quota_type', 'bytes_in_avail', 'bytes_out_avail', 'limit_type'];

    /**
     * @var string
     */
    protected $table = 'ftpquotalimits';

    /**
     * @var bool
     */
    public $timestamps = false;
}
