<?php

namespace App\Policies\License;

use App\Models\License;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LicensePolicy
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
     * @param License $license
     * @return bool
     */
    public function showLicense (User $user, License $license): bool
    {
        return true;
    }
}
