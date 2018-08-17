<?php

namespace App\Listeners;

use App\Events\AbstractActivityEvent;
use App\Models\Activity;

class CreateNewActivityListener
{
    /**
     * @param AbstractActivityEvent $event
     * @throws \Exception
     */
    public function handle(AbstractActivityEvent $event): void
    {
        $activity = new Activity();
        $activity->type = $event->type;

        switch ($activity->type) {
            case Activity::LICENSE_TYPE:
                $union = ': Changed license for ';
                break;
            case Activity::UPLOAD_MEDIA_TYPE:
                $union = ': Uploaded photo ';
                break;
            case Activity::EDIT_MEDIA_TYPE:
                $union = ': Edited photo ';
                break;
            case Activity::DELETE_MEDIA_TYPE:
                $union = ': Deleted photo ';
                break;
            case Activity::MANAGE_CREATIVE_TYPE:
                $union = ' joined the ';
                break;
            default:
                throw new \Exception('Unknown Activity type');
        }

        $activity->message = $event->getOrigin()->originActivityText() . $union . $event->getTarget()->targetActivityText();

        $activity->brand()->associate($event->getBrand());
        $activity->save();
    }

}
