<?php

use Illuminate\Database\Seeder;

class VouchersSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return  void
	 */
	public function run()
	{
		factory(\App\Models\Voucher::class, 10)->create();
	}
}
