<?php

// namespace
namespace App\Services;

// use
use App\Models\Creative;
use App\Models\FTPUser;
use App\Models\Brand;
use Log;

/**
 * Class FTPService
 *
 * @package App\Services
 */
class FTPService
{
    /**
     * @param Brand $brand
     * @param string $password
     * @param string $username
     * @param Creative $creative
     * @return FTPUser
     */
    public static function makeFTPUserForBrand(Brand $brand, $username, $password, Creative $creative = null): FTPUser
    {
        if($creative === null) {

            return new FTPUser([
                'userid' => $username,
                'passwd' => $password,
                'brand_id' => $brand->id,
                'user_id' => $brand->user->id,
                'homedir' => storage_path('app/brands/' . $brand->getImagePath()),
                'uid' => 33,
                'gid' => 33
            ]);

        } else {

            return new FTPUser([
                'userid' => $username,
                'passwd' => $password,
                'brand_id' => $brand->id,
                'creative_id' => $creative->id,
                'user_id' => $creative->user->id,
                'homedir' => storage_path('app/brands/' . $brand->getImagePath()),
                'uid' => 33,
                'gid' => 33
            ]);
        }
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