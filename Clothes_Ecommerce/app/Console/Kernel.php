<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Token;
use App\Models\Product;


use Maatwebsite\Excel\Facedes\Excel;
use App\Http\Resources\Transaction\TransactionCollection;



class Kernel extends ConsoleKernel
{
    
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule -> call(function(){ // 
            Token::where("expires_at","<", Carbon::now())->delete();
        })-> everySecond();
        // $schedule -> call(function(){ // 
        //     $transactions = Transaction::all()
        //     ->where('status','=','2')
        //     ->whereBetween('updated_at',[Carbon::now()->addDays(-1)->startOfDay(),Carbon::now()->addDays(-1)->endOfDay()]);
        // if(count($transactions) > 0){
        //     //Excel::store(new TransactionCollection($yesterdayTransactions), 'dailyList.xlsx');
        // }                            
        // })->everySecond();
        //})-> daily();
        //$schedule -> call('\App\Services\UserService@removeTokenDaily')->everyMinute();
        $schedule -> call(function(){ // end pending transactions daily
            $transactions =Transaction::where("status","=", 0)->get();
            foreach($transactions as $transaction){
                foreach($transaction->transaction_products()->get() as $order){
                    $product = Product::find($order->product_id);
                    if($product == null){ // check null
                        continue;
                    }
                    $product->available += $order->amount;
                    $product->save();
                }
                $transaction->status = 3;
                $transaction->save();
            }
        })-> everySecond();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
