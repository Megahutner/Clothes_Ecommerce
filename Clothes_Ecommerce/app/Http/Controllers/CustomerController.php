<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\ResetPasswordRequest;

use App\Http\Controllers\Controller;
    use App\Http\Resources\Customer\CustomerResource;
    use App\Http\Resources\Customer\CustomerCollection;
use App\Filter\CustomerFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requests\BulkStoreCustomerRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\UserVerificationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Token;
use Carbon\Carbon;

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
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request) // register API
    {
        $customer = new Customer();
        $input = $request->all();
        $customer->name = $input['name'];
        $customer->password = Hash::make($input['password']);
        $customer->address = $input['address'];
        $customer->phone = $input['phone'];
        $customer->email = $input['email'];
        $token = Str::random(60);
        $customer->remember_token= $token;
        $customer->code = (string)random_int(100000,999999);
        $customer->status = "Inactive";
        $customer -> save();
        Mail::to($customer->email)-> send(new UserVerificationMail($customer->code));
        $data=[
            "code" => 200,
            "message" => "Success",
            "api_token" =>  $token,
            "user" => new CustomerResource($customer)
        ];
        return response()->json($data);
    }

    public function sendResetMail(Request $request){
        $input = $request->all();
        $data=[
            "code" => 0,
            "message" => "",
        ];
        $customer = Customer::where('name', '=', $input['name'])->first();
        if ($customer == null){
            $data['code'] = 422;
            $data['message'] = "No customer found";
            return response()->json($data);
        }
        $customer->code = (string)random_int(100000,999999);
        $customer->save();
        Mail::to($customer->email)-> send(new UserVerificationMail($customer->code));
        $data['code'] = 200;
        $data['message'] = "Success";
        return response()->json($data);

    }
    public function resetPassword(ResetPasswordRequest $request){
        $data=[
            "code" => 0,
            "message" => "",
        ];
        $input = $request->all();
        $customer = Customer::where('name', '=', $input['name'])->first();
        if ($customer == null){
            $data['code'] = 422;
            $data['message'] = "No customer found";
            return response()->json($data);
        }
        if ($customer->code != $input['code']){
            $data['code'] = 422;
            $data['message'] = "Unmatched PIN code";
            return response()->json($data);
        }
        $customer->password = Hash::make($input['newPassword']);
        $customer->save();
        $data['code'] = 200;
        $data['message'] = "Success";
        return response()->json($data);
        // if (!Hash::check($input['oldPassword'], $cryptedpassword)) {
        //     $data['code'] = 422;
        //     $data['message'] = "Wrong ";
        //     return response()->json($data);
        // }
    }
    public function verify(Request $request){
        $data=[
            "code" => 0,
            "message" => "",
        ];
        $input = $request->all();
        $code = 0;
        $customer = Customer::where('name', '=', $input['name'])->first();
        if ($customer == null){
            $data['code'] = 422;
            $data['message'] = "No customer found";
            return response()->json($data);
        }
        if ($customer->code != $input['code']){
            $data['code'] = 422;
            $data['message'] = "Unmatched PIN code";
            return response()->json($data);
        }
        $customer->status = "Active";
        $customer->save();
        $existedToken = Token::where('token','=',$customer->remember_token)->first();
        if($existedToken != null){
            $existedToken-> token = $customer->remember_token;
            $existedToken-> expires_at = Carbon::now()->addMinutes(2);
            $existedToken->save();
        }
        else{
            $token = new Token;
            $token->token = $customer->remember_token;
            $token->expires_at = Carbon::now()->addMinutes(2) ;
            $token->save();
        }
        $data['code'] = 200;
        $data['message'] = "Success";
        return response()->json($data);
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
            'code' => 200,
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

    public function login(Request $request) {  
        
        $name=$request->input('name');
        $password=$request->input('password');        
        $model = Customer::where('name', '=', $name)->first();
        //dd($email);    
        if (!empty($model)) {
            $cryptedpassword=$model->password;
            if (Hash::check($password, $cryptedpassword)) {
               
                $api_token = Str::random(60);
                //$this->LoggedIn->push($api_token);
                $existedToken = Token::where('token','=',$model->remember_token)->first();
                if($existedToken != null){
                    $existedToken-> token = $api_token;
                    $existedToken->save();
                }
                else{
                    $token = new Token;
                    $token->token = $api_token;
                    $token->expires_at = Carbon::now()->addMinutes(2) ;
                    $token->save();
                }
                $model->remember_token= $api_token;
                $model->save();
                $data=[
                    "code" => 200,
                    "status" => "Success",
                    "api_token" =>  $api_token,
                    "user" => $model->name
                ];
            } else {
                $data=[
                    "code" => 401,
                    "status" => "Password did not match.",
                    "user" => null
                ];
            }
        } 
        if(empty($model)) {
            $data=[
                "code" => 402,
                "status" => "User not found.",
                "user" => null
            ];
        }        
        return response()->json($data);
      }

      public function logout(Request $request){
        $token = $request->bearerToken();
        Token::where("token",'=', $token)->delete();
        $data=[
            "code" => 200,
            "status" => "Success",
        ];
        return response()->json($data);
    }
}
