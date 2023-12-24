<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\CustomerCollection;
use App\Filter\CustomerFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requests\BulkStoreCustomerRequest;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new CustomerFilter();
        $filterItems = $filter->transform($request);  //[['column','operator','value']]
        $includeTransactions = $request->query('includeTransactions');
        $customers = Customer::where($filterItems);
        if($includeTransactions){
            $customers = $customers->with('transactions');
        }
        return new CustomerCollection($customers->paginate()-> appends($request->query()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreCustomerRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    public function bulkStore(BulkStoreCustomerRequest $request){
        $bulk = collect($request->all());
        Customer::insert($bulk->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //return $customer;
        $includeTransactions = request()->query('includeTransactions');
        if($includeTransactions){
            return new CustomerResource($customer->loadMissing('transactions'));
        }
        //return new CustomerResource($customer);
        //return Response::json((new CustomerResource($customer)),'200');
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new CustomerResource($customer)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
