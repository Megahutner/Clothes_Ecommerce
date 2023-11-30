<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prod_cat = Product::factory()
        ->count(10)->create()->each(function(Product $product){
            Category::factory()-> count(1)->create();
        });
        Brand::factory()
        -> count(1)
        -> has($prod_cat)
        -> create();
    }
}
