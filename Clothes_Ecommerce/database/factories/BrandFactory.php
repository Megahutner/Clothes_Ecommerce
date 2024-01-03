<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            $name = $this->faker-> randomElement(['Gucci','Nike','Coolmate','Bitis']);
            return [
                'name' => $name,
                'description' => $this->faker->text(),
            ];
        
    }
}
