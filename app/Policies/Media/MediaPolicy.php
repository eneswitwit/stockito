<?php

namespace App\Policies\Media;

use App\Models\Media;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Auth\Access\HandlesAuthorization;
use Log;

class MediaPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @param Media $media
     * @return bool
     */
    public function showMedia(User $user, Media $media): bool
    {
        return $user->brand ? $user->brand->id === $media->brand_id : $media->brand->creatives->where('id', '=', $user->creative->id)->count();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function upload(User $user): bool
    {
        if ($brand = $user->brand) {
            if ($user->subscribed('main')) {
                if ($ftpUser = $brand->getProduct()) {
                    return $brand->getProduct()->storage > UploadService::calculateUsedStorage($brand)['used'];
                }
            }
        }

        return false;
    }
}
