<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Brand\BrandCollection;

use App\Http\Controllers\Controller;
use App\Filter\BrandFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Token;
use Carbon\Carbon;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new BrandFilter();
        $filterItems = $filter->transform($request);  //[['column','operator','value']]
        //$includeTransactions = $request->query('includeTransactions');
        $brands = Brand::where($filterItems);
        // if($includeTransactions){
        //     $customers = $customers->with('transactions');
        // }
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new BrandCollection($brands->paginate()-> appends($request->query()))
        ]);
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
    public function store(StoreBrandRequest $request)
    {
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new BrandResource(Brand::create($request->all()))
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new BrandResource($brand)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $validated = $request->validated();
        $brand->update($validated);
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new BrandResource($brand)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
