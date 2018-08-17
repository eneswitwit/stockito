<?php

namespace App\Console\Commands;

use App\Managers\MediaManager;
use App\Models\Media;
use Illuminate\Console\Command;

class CheckMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check existing media files for media object';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $medias = Media::all();

        foreach ($medias as $media) {
            $this->info('Brand '.$media->brand_id.' : Checking '.$media->file_name);
            if (!MediaManager::hasMediaFile($media)) {
                $media->delete();
                $this->info('Object deleted without file '.$media->file_name);
            }
        }
    }
}
