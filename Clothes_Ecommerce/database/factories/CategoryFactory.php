<?php

namespace Database\Factories;
use App\Models\Category;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
            // $name = $this->faker-> randomElement(['Pants','Shirt','Shorts','Hat','Undergarments']);
            // return [
            //     'name' => $name,
            //     'description' => $this->faker->text(),
            // ];
        
    }
}
