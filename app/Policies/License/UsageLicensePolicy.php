<?php

// namespace
namespace App\Policies\License;

// use
use App\Models\UsageLicense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UsageLicensePolicy
 *
 * @package App\Policies\License
 */
class UsageLicensePolicy
{
    use HandlesAuthorization;

    /**
     * LicensePolicy constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @param UsageLicense $license
     * @return bool
     */
    public function showLicense (User $user, UsageLicense $license): bool
    {
        return true;
    }
}
