<?php

use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * @var \App\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * PlanSeeder constructor.
     * @param \App\Repositories\ProductRepository $productRepository
     */
    public function __construct(\App\Repositories\ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        if (!\App\Models\Brand::count()) {
            $this->call(BrandSeeder::class);
        }


        $plans = factory(\App\Models\Plan::class, 10)->make();

        foreach ($plans as $plan) {
            $this->productRepository->create([
                'price' =>  $plan->price,
                'description' => $plan->title,
                'title' =>  $plan->title,
                'interval' =>  $plan->interval,
                'storage' => $plan->storage,
                'video_storage' => $plan->video_storage,
            ]);
        }

    }
}
