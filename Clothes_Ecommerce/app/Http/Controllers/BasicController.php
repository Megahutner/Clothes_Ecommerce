<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionCollection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Carbon\Carbon;


class BasicController extends Controller
{
    public function generalStatistics(){
        $product= Product::all();
        $category = Category::all();
        $customer = Customer::where('status','=','Active')->get();
        $staff = User::all();
        $transaction = Transaction::where('status','=','2')->get();
        $data=[
            "product" => $product->count(),
            "category" => $category->count(),
            "customer" => $customer->count(),
            "staff" => $staff->count(),
            "transaction" => $transaction->count(),

        ];
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function latestCustomerReg(){
        $customer = Customer::where('status','=','Active')
        ->select('name','email','city','phone','created_at','updated_at')
        ->latest()->take(10)->get();
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => $customer
        ]);
    }

    public function latestTransactions(){
        $transaction = Transaction::where('status','=','2')
        ->select('id','transactionId','total','payment','updated_at')
        ->latest()->take(10)->get();
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => $transaction
        ]);
    }

    public function currentMonthStatistic(Request $request){
        $transactions = Transaction::where('status','=','2')
        ->whereBetween('updated_at',[Carbon::now()->addDays(-1)->startOfDay(),Carbon::now()->addDays(1)->endOfDay()])->get()
        ->groupBy(function($val){
            return Carbon::parse($val->updated_at)->format('d');
        });
        if($transactions->count() > 0){
            return response()->json([
                'code' => '200',
                'message' => 'success',
                'data' => $transactions
            ]);
        }
        else{
            return response()->json([
                'code' => '422',
                'message' => 'failed',
            ]);
        }
    }
  
}
