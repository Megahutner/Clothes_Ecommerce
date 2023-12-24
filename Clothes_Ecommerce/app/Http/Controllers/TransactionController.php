<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Transaction;
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
        //
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
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
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

    public function currentMonthStatistic(Request $request){
        $transactions = Transaction::all()
        ->where('status','=','2')
        ->whereBetween('updated_at',[Carbon::now()->addDays(-1)->startOfDay(),Carbon::now()->addDays(-1)->endOfDay()]);
        if(count($transactions) > 0){
            return response()->json([
                'code' => '200',
                'message' => 'success',
                'data' => new TransactionCollection($transactions)
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
