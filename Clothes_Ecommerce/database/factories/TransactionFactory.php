<?php

namespace Database\Factories;
use App\Models\Customer;
use App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
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
        $date = $this->faker->dateTimeThisYear();
        return [
            'customer_id'=> Customer::factory(),
            'transactionId'=>$this->faker->regexify('[A-Za-z0-9]{10}'),
            'total'=> $this->faker->randomFloat('2',10,500),
            'payment' =>$status == '2' ? $payment : "",
            'status'=> $status,
            'created_at' => $date,
            'updated_at' => $status != '1' ? $date: NULL,
        ];
    }
}


// $table->integer('customer_id');
// $table->string('transactionId');
// $table->float('total');
// $table->string('payment');
// $table->tinyInteger('status');
// $table->timestamps();
