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
class DowngradeSubscription extends Notification
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
        return (new MailMessage)->line('You requested a downgrade. The downgrade will be automatically executed after the expiration of your current plan.');
    }
}
