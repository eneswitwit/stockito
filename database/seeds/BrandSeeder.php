<?php

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = \App\Models\Country::all();
        if (!\App\Models\Creative::count()) {
            $this->call(CreativeSeeder::class);
        }

        $creatives = \App\Models\Creative::all();

        factory(\App\Models\Brand::class, 30)->make()->each(function (\App\Models\Brand $brand) use ($countries, $creatives) {
            $addCreative = (bool)random_int(0,1);
            $brand->user()->associate(factory(\App\Models\User::class)->create());
            $brand->country()->associate($countries->random());

            $brand->save();

            if ($addCreative) {
                foreach ($creatives->random(random_int(1,3)) as $creative) {
                    $brand->creatives()->attach($creative->id, ['role' => 'Some Role', 'position' => array_rand(\App\Models\Brand::getPermissions())]);
                    event(new \App\Events\CreativeJoinedToBrandEvent($creative, $brand));
                }
            }
        });
    }
}
