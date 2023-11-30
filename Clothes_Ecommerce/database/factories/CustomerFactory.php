<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['Active','Inactive']);
        $startTime = '1999-01-01';
        $endTime = '2010-12-31';
        return [
            'name' => $this->faker->name(),
            'city' => $this->faker->city(),
            'email' => $this->faker->email(),
            'address' => $this->faker->address(),
            'dob'=> $this->faker->date('Y-m-d',rand(strtotime($startTime),strtotime($endTime))),
            'phone'=>$this->faker->phoneNumber(),
            'password'=>$this->faker->password(),
            'status'=>$status,
        ];
    }
}


// $table->string('name')->unique();
// $table->string('address');
// $table->string('dob');
// $table->string('email')->unique();
// $table->string('password');
// $table->string('city');
// $table->string('phone');
// $table->string('status');