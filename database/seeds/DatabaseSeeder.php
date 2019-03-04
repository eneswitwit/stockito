<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountriesSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(CreativeSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(MediaSeeder::class);
        $this->call(LicenseSeeder::class);
        $this->call(VouchersSeeder::class);
        $this->call(PlanSeeder::class);
    }
}
