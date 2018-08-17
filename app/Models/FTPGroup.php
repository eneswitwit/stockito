<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FTPGroup
 *
 * @property int $id
 * @property string $groupname
 * @property int $gid
 * @property string $members
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPGroup whereGid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPGroup whereGroupname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPGroup whereMembers($value)
 * @mixin \Eloquent
 */
class FTPGroup extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['groupname', 'members', 'gid'];

    /**
     * @var string
     */
    protected $table = 'ftpgroup';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return Model|null|object|static
     */
    public function getQuotaLimit()
    {
        return (new FTPQuotaLimits())->where('name', $this->groupname)->where('quota_type', FTPQuotaLimits::GROUP_QUOTA_TYPE)->first();
    }
}
