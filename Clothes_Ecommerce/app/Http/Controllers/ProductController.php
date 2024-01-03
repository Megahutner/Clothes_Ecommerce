<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Category\CategoryCollection;


use App\Http\Controllers\Controller;
use App\Filter\ProductFilter;
use App\Filter\CategoryFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Token;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new ProductFilter();
        $filterItems = $filter->transform($request);  //[['column','operator','value']]
        //$includeTransactions = $request->query('includeTransactions');
        $products = Product::where($filterItems)->get();
        // if($includeTransactions){
        //     $customers = $customers->with('transactions');
        // }
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => $products
        ]);
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
    public function store(StoreProductRequest $request)
    {
        $input = $request->all();
        $category = Category::find($input['category_id']);
        if($category == null){
            return response()->json([
                'code' => 422,
                'message' => 'Non-exist category',
            ]);   
        }
        $products = [];
        $product = Product::create($request->all());
        // array_push($products,$product);
        // $category->products()->saveMany($products);
        $product->category()->associate($category);
        $product->save();
        $category->save();
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if($product == null){
          return response()->json([
              'code' => '422',
              'message' => 'Non-exist product',
          ]);
        }
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $validated = $request->validated();
        $product->update($validated);
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $product->update($validated);
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' =>new ProductResource($product)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if($product == null){
          return response()->json([
              'code' => '422',
              'message' => 'Non-exist product',
          ]);
        }
        $product->delete();
        return response()->json([
            'code' => '200',
            'message' => 'success',
        ]);
    }

    public function customerShow(Request $request){
        $filter = new CategoryFilter();
        $filterItems = $filter->transform($request);  //[['column','operator','value']]
        //$includeTransactions = $request->query('includeTransactions');
        $products = Category::where($filterItems)->with('products');
        // if($includeTransactions){
        //     $customers = $customers->with('transactions');
        // }
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => new CategoryCollection($products->paginate()->appends($request->query())) 
        ]);
    }
}
