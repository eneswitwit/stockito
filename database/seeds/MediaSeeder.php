<?php

use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!\App\Models\Brand::count()) {
            $this->call(BrandSeeder::class);
        }
        if (!\App\Models\Tag::count()) {
            $this->call(TagSeeder::class);
        }
        $brands = \App\Models\Brand::all();
        $tags = \App\Models\Tag::all();

        factory(\App\Models\Media::class, 300)->make()->each(function (\App\Models\Media $m) use ($brands, $tags) {
            $m->brand()->associate($brands->random());
            $m->save();

            $m->tags()->attach($tags->random(random_int(1,5)));
        });
    }
}
