<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\PaymentLog;
use App\Models\Transaction;
use App\Models\User;



class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory()
         -> count(25)
         -> has(Transaction::factory()
         -> count(25)
         -> hasPaymentLogs(1)
          )
         -> create();
         User::factory()
         -> count(25)
         -> create();
    }
}
