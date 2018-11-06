<?php

// namespace
namespace App\Notifications;

// use
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as Notification;

/**
 * Class MailUserRegistration
 *
 * @package App\Notifications
 */
class UpgradeSubscription extends Notification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('You upgraded your Subscription')->line('You upgrade your Subscription. You can find the corresponding invoice in your profile.');
    }
}
