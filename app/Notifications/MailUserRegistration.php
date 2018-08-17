<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as Notification;

class MailUserRegistration extends Notification
{
  /**
   * Build the mail representation of the notification.
   *
   * @param  mixed $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $activateLink = url('/email/confirm/' . $this->token);

    return (new MailMessage)
      ->line('You receive this email because you are registering an account.')
      ->action('Confirm Email', $activateLink);
  }
}
