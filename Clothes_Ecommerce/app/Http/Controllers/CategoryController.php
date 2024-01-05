<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Controllers\Controller;
use App\Filter\CategoryFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Token;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new CategoryFilter();
        $filterItems = $filter->transform($request);  //[['column','operator','value']]
        //$includeTransactions = $request->query('includeTransactions');
        $categories = Category::where($filterItems);
        // if($includeTransactions){
        //     $customers = $customers->with('transactions');
        // }
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => new CategoryCollection($categories->paginate()-> appends($request->query()))
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
    public function store(StoreCategoryRequest $request)
    {
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => new CategoryResource(Category::create($request->all()))
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        
        //   $includeTransactions = request()->query('includeTransactions');
        //   if($includeTransactions){
        //       return new CustomerResource($customer->loadMissing('transactions'));
        //   }
          //return new CustomerResource($customer);
          //return Response::json((new CustomerResource($customer)),'200');
          $category = Category::find($id);
          if($category == null){
            return response()->json([
                'code' => 422,
                'message' => 'Non-exist category',
            ]);
          }
          return response()->json([
              'code' => 200,
              'message' => 'success',
              'data' => new CategoryResource($category)
          ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $category->update($validated);
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if($category == null){
          return response()->json([
              'code' => 422,
              'message' => 'Non-exist category',
          ]);
        }
        $category->delete();
        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }
}
