<?php

// namespace
namespace App\Notifications;

// use
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as Notification;

/**
 * Class ResetPassword
 *
 * @package App\Notifications
 */
class ResetPassword extends Notification
{
  /**
   * Build the mail representation of the notification.
   *
   * @param  mixed $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $resetLink = url('/password/reset/' . $this->token) . '?email=' . urlencode($notifiable->email);

    return (new MailMessage)
      ->line('You are receiving this email because we received a password reset request for your account.')
      ->action('Reset Password', $resetLink)
      ->line('If you did not request a password reset, no further action is required.');
  }
}
