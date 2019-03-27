<?php

// namespace
namespace App\Mail;

// use
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class StorageFull
 *
 * @package App\Mail
 */
class StorageFull extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * StorageFull constructor.
     */
    public function __construct() {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self {
        return $this->markdown( 'mails.storage-full')->subject('Your storage is full!');
    }
}
