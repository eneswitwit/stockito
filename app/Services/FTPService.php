<?php

// namespace
namespace App\Services;

// use
use App\Models\FTPUser;
use App\Models\Brand;

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
     * @return FTPUser
     */
    public static function makeFTPUserForBrand(Brand $brand, $password): FTPUser
    {
        return new FTPUser([
            'userid' => $brand->user->email,
            'passwd' => $password,
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