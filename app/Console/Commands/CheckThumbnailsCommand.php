<?php

namespace App\Console\Commands;

use App\Managers\MediaManager;
use App\Models\Media;
use Illuminate\Console\Command;

class CheckThumbnailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check media thumbnails and recreate, it it needed';

    /**
     * @var MediaManager
     */
    protected $mediaManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        /**
         * @var Media[] $medias
         */
        $medias = Media::all();

        foreach ($medias as $media) {
            if ($media->content_type === Media::JPEG_MIME && $this->mediaManager::hasMediaFile($media) && !$this->mediaManager::hasThumbnail($media)) {
                $this->info('Brand '.$media->brand_id.' :Creating thumbnail for '.$media->file_name);
                $this->mediaManager->makeAndStoreThumbnail($media);
            }
        }
    }
}
