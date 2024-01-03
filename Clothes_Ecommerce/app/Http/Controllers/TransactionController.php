<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\transaction_product;
use App\Models\Product;
use Stripe;



use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\Transaction\TransactionCollection;
use App\Filter\TransactionFilter;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //return new TransactionCollection(Transaction::paginate());
        $filter = new TransactionFilter();
        $queryItems = $filter->transform($request);  //[['column','operator','value']]
        if(count($queryItems)==0){
            return new TransactionCollection(Transaction::paginate());
        }
        else{
            $transactions = Transaction::where($queryItems)->paginate();
            return new TransactionCollection($transactions->appends($request->query()));
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);
        if($transaction == null){
          return response()->json([
              'code' => '422',
              'message' => 'Non-exist transaction',
          ]);
        }
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new TransactionResource($transaction)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }


    public function addToCart(Request $request){
        $input = $request->all();
        if($request->bearerToken()== null){
            return response()->json([
                'code' => '422',
                'message' => 'Non-exist customer',
            ]);
        } 
        try{
            $product = Product::find($input['product_id']);
            if($product == null){ // check null
                return response()->json([
                    'code' => '422',
                    'message' => 'Non-exist product',
                ]);
            }
            if($product->available < $input['amount']){ // check if db has enough product to sell
                return response()->json([
                    'code' => '422',
                    'message' => 'Not enough product',
                ]);
            }
            $latestTransaction = Customer::where('remember_token','=',$request->bearerToken())->first()->transactions()->latest()->first();
        if ($latestTransaction == null || $latestTransaction->status != 0){ // create new Transaction as a cart
            $transaction = new Transaction();
            $transaction->customer_id= Customer::where('remember_token','=',$request->bearerToken())->first()->id;
            $order = new transaction_product();
            $transaction->save();
            $order->transaction_id = $transaction->id;
            $order->product_id = $input['product_id'];
            $order->product_name = $product->name;
            $order->price = $input['price'];
            $order->amount = $input['amount'];
            $order->created_at = Carbon::now();
            $order->updated_at = Carbon::now();
            $order->transaction()->associate($transaction);
            $order->save();
            $transaction->total += $order->amount * $order->price;
            $transaction->save();
            $product->available -= $order->amount;
            $product->save();
            return response()->json([
                'code' => '200',
                'message' => 'success',
                'data' => new TransactionResource($transaction)
            ]);
            
        }
        else{ // add to cart
            $order = new transaction_product();
            $order->transaction_id = $latestTransaction->id;
            $order->product_id = $input['product_id'];
            $order->product_name = $product->name;
            $order->price = $input['price'];
            $order->amount = $input['amount'];
            $order->transaction()->associate($latestTransaction);
            $order->save();
            $latestTransaction->total += $order->amount * $order->price;
            $latestTransaction->save();
            $product->available -= $order->amount;
            $product->save();
            return response()->json([
                'code' => '200',
                'message' => 'success',
                'data' => new TransactionResource($latestTransaction)
            ]);
        }
        
        }
        catch(Exception $ex){
            return response()->json([
                'code' => '422',
                'message' => $ex,
            ]);
        }
    }


    public function removeFromCart(Request $request){
        $input = $request->all();
        try{
            $latestTransaction = Customer::where('remember_token','=',$request->bearerToken())->first()->transactions()->latest()->first();
            $order = $latestTransaction->transaction_products()->where('id','=',$input['order_id'])->first();
            if($order == null){
                return response()->json([
                    'code' => '422',
                    'message' => 'Non-exist order',
                ]);
            }
            $product = Product::find($order->product_id);
            if($product == null){ // check null
                return response()->json([
                    'code' => '422',
                    'message' => 'Non-exist product',
                ]);
            }
            $latestTransaction->total  -= $order->price * $order->amount;
            $product->available += $order->amount;
            $latestTransaction->save();
            $product->save();
            $order->delete();
            return response()->json([
                'code' => '200',
                'message' => 'success',
                'data' => new TransactionResource($latestTransaction)
            ]);
        }
        catch(Exception $ex){
            return response()->json([
                'code' => '422',
                'message' => $ex,
            ]);
        }
    }

    public function toCheckOut(Request $request){
        $input = $request->all();
        $transaction = Transaction::find($input['transaction_id']);
        if($transaction == null){
            return response()->json([
                'code' => '422',
                'message' => 'Non-exist transaction',
            ]);
        }
        if($transaction->status != 0){
            return response()->json([
                'code' => '422',
                'message' => 'Invalid transaction',
            ]);
        }
        $transaction->status = 1; // Pending
        $transaction->save();
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new TransactionResource($transaction)
        ]);
    }

    public function makeTransaction (Request $request){
        try
        {
            $transaction = Transaction::find($request->transaction_id);
        if($transaction == null ){
            return response()->json([
                'code' => '422',
                'message' => 'Non-exist transaction',
            ]);
        }
        if( $transaction->status != 1){
            return response()->json([
                'code' => '422',
                'message' => 'Invalid transaction',
            ]);
        }
        $transaction->status = 2;
        $transaction->save();
            // $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            // $res = $stripe->tokens->create([
            //     'card'=> [
            //         'number' => $request->number,
            //         'exp_month' => $request->exp_month, 
            //         'exp_year' => $request->exp_year, 
            //         'cvc' => $request->cvc, 
            //     ]
            //     ]);
            //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            // $response = $stripe->charges->create([
            //     'amount' => $transaction->total,
            //     'currency' => 'gdp',
            //     'source' => $res->id,
            //     'description'=> $res->description
            // ])    ;

            // $response = $stripe->paymentIntents->create([
            //     'amount'=> (int)$transaction->total,
            //     'currency'=>'usd',
            //     'payment_method'=> 'pm_card_visa'
            // ]);
            return response()->json([
                'code' => '200',
                'message' => 'success',
                'data' => new TransactionResource($transaction),
            ]);
        }
        catch (Exception $ex)
        {
            return response()->json([
                'code' => '422',
                'message' => $ex,
            ]);
        }
    }

    public function endTransaction(Request $request){
        $input = $request->all();
        try{
            $transaction = Transaction::find($input['transaction_id']);
            if($transaction == null){
                return response()->json([
                    'code' => '422',
                    'message' => 'Non-exist transaction',
                ]);
            }
            if($transaction->status == 2 && $transaction->status == 0 ){
                return response()->json([
                    'code' => '422',
                    'message' => 'Invalid transaction',
                ]);
            }
            foreach($transaction->transaction_products()->get() as $order){
                $product = Product::find($order->product_id);
                if($product == null){ // check null
                    return response()->json([
                        'code' => '422',
                        'message' => 'Non-exist product',
                    ]);
                }
                $product->available += $order->amount;
                $product->save();
            }
            $transaction->status = 3;
            $transaction->save();
            return response()->json([
                'code' => '200',
                'message' => 'success',
                'data' => new TransactionResource($transaction)
            ]);
        }
        catch(Exception $ex){
            return response()->json([
                'code' => '422',
                'message' => $ex,
            ]);
        }
    }


}
