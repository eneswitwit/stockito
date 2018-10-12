<?php

// namespace
namespace App\Models;

// use
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\FTPUser
 *
 * @property int $id
 * @property string $userid
 * @property string $passwd
 * @property int $uid
 * @property int $gid
 * @property string $homedir
 * @property string $shell
 * @property int $count
 * @property string $accessed
 * @property string $modified
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser whereAccessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser whereGid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser whereHomedir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser wherePasswd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser whereShell($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FTPUser whereUserid($value)
 * @mixin \Eloquent
 * @property-read FTPGroup|null $ftp_group
 * @property-read \App\Models\Brand $brand
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FTPFile[] $ftpFiles
 */
class FTPUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'ftpuser';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'homedir',
        'userid',
        'passwd',
        'uid',
        'gid',
    ];

    /**
     * @return FTPGroup|null
     */
    public function getFtpGroup(): ?FTPGroup
    {
        return (new FTPGroup)->where('members', 'LIKE', '%' . $this->groupname . '%')->first();
    }

    /**
     * @return HasMany
     */
    public function ftpFiles(): HasMany
    {
        return $this->hasMany(FTPFile::class, 'username', 'userid');
    }

    /**
     * @return HasOne
     */
    public function brand(): HasOne
    {
        return $this->hasOne(Brand::class, 'ftp_user_id');
    }


    /**
     * @return FTPGroup|null
     */
    public function getFtpGroupAttribute(): ?FTPGroup
    {
        return $this->getFtpGroup();
    }
}
