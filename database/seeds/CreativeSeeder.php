<?php

use Illuminate\Database\Seeder;

class CreativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Creative::class, 30)->make()->each(function (\App\Models\Creative $m) {
            $m->user()->associate(factory(\App\Models\User::class)->create());
            $m->save();
        });
    }
}
