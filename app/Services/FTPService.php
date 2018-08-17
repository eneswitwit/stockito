<?php

namespace App\Services;

use App\Models\FTPUser;
use App\Models\Brand;

class FTPService
{
    /**
     * @param Brand $brand
     * @return FTPUser
     */
    public static function makeFTPUserForBrand(Brand $brand): FTPUser
    {
        return new FTPUser([
            'userid' => self::getUniqueUserId(),
            'passwd' => str_random(8),
            'homedir' => storage_path('app/brands/'.$brand->getImagePath()),
            'uid' => 33,
            'gid' => 33
        ]);
    }

    /**
     * @param Brand $brand
     * @return bool
     */
    public static function checkExistFTPUserForBrand(Brand $brand): bool
    {
        return (bool)$brand->ftpUser;
    }

    /**
     * @param $userId
     * @return bool
     */
    protected static function checkExistUserId($userId): bool
    {
        return (bool)(new FTPUser)->where('userid', $userId)->count();
    }

    /**
     * @return string
     */
    protected static function getUniqueUserId(): string
    {
        $exist = true;
        while ($exist) {
            $userid = str_random(8);
            $exist = self::checkExistUserId($userid);
        }
        return $userid;
    }
}