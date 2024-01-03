<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Brand;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $available = $this->faker->numberBetween(0,100);
        return [
            'name'=> $this->faker->name(),
            'brand_id'=> Brand::factory(),
            'description'=> $this->faker->text(),
            'productId'=>$this->faker->regexify('[A-Za-z0-9]{10}'),
            'price'=> $this->faker->randomFloat('2',10,100),
            'available'=>$available,
        ];
    }
}

// $table->id();
// $table->string('name');
// $table->string("brand_id");
// $table->string('description')->nullable();
// $table->string('productId');
// $table->float('price');
// $table->integer('available');
// $table->string('image');
// $table->tinyInteger('status');
