<?php

use Illuminate\Database\Seeder;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!\App\Models\Media::count()) {
            $this->call(MediaSeeder::class);
        }
        $medias = \App\Models\Media::all();

        factory(\App\Models\License::class, 100)->make()->each(function (\App\Models\License $m)  use ($medias) {
            $media = $medias->random();
            $m->createdBy()->associate($media->brand->user);
            $m->updatedBy()->associate($media->brand->user);
            $m->save();
            $media->license()->associate($m);
            $media->save();
        });
    }
}
