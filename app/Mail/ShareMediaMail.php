<?php

// namespace
namespace App\Mail;

// use
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class ShareMediaMail
 *
 * @property \Illuminate\Database\Eloquent\Collection $medaias
 * @property string $text
 *
 * @package App\Mail
 */
class ShareMediaMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var
     */
    protected $medias;

    /**
     * @var string
     */
    protected $text;

    /**
     * ShareMediaMail constructor.
     * @param $media
     * @param string $text
     */
    public function __construct($media, string $text = '')
    {
        $this->medias = $media;
        $this->text = $text;
    }

    /**
     * Build message
     *
     * @return \App\Mail\ShareMediaMail
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function build(): self
    {
        $mail = $this->view('mails.share-media', ['text' => $this->text]);

        foreach ($this->medias as $media) {
            $localFile = storage_path('app/brands/' . $media->dir . '/' . $media->file_name);
            $remoteFile = \Storage::disk('s3')->get($media->getFilePath());
            file_put_contents($localFile, $remoteFile);
            $mail->attach($localFile, ['as' => $media->origin_name, 'mime' => $media->content_type]);
        }

        return $mail;
    }
}
