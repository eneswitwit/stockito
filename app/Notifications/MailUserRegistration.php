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
class MailUserRegistration extends Notification
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
        $activateLink = url('/email/confirm/' . $this->token);

        return (new MailMessage)
            ->line('You received this email because you signed up for Stockito. Please confirm your Email by clicking the following button.')
            ->action('Confirm Email', $activateLink);
    }
}
