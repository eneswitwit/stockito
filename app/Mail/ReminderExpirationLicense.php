<?php

// namespace
namespace App\Mail;

// use
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class ReminderExpirationLicense
 *
 * @package App\Mail
 */
class ReminderExpirationLicense extends Mailable
{
    use Queueable, SerializesModels;

    public $licenseType;
    public $mediaNumber;
    public $invoiceNumber;
    public $daysLeft;


    /**
     * ReminderExpirationLicense constructor.
     *
     * @param $licenseType
     * @param $mediaNumber
     * @param $invoiceNumber
     * @param $daysLeft
     */
    public function __construct($licenseType, $mediaNumber, $invoiceNumber, $daysLeft) {
        $this->licenseType = $licenseType;
        $this->mediaNumber = $mediaNumber;
        $this->invoiceNumber = $invoiceNumber;
        $this->daysLeft = $daysLeft;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self {
        return $this->markdown( 'mails.remindLicenseExpiration')->subject('Your license is expiring!');
    }
}
