<?php

namespace Database\Factories;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentLog>
 */
class PaymentLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            $status = $this->faker->randomElement([1,2,3]);
            $payment =$this->faker->randomElement(['ZaloPay','Momo','Credit Card']);
            return [
                'transaction_id'=>Transaction::factory(),
                'amount'=> $this->faker->randomFloat('2',10,500),
                'status'=> $status,
                'created_at' => $this->faker->dateTimeThisDecade(),
                'updated_at' => $status != '1' ? $this->faker->dateTimeThisDecade() : NULL,
            ];
    }
}
