<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Token;

use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use App\Services\UserService;



class AdminController extends Controller
{
    protected $userService;

    // Inject the service into the constructor
    public function __construct(UserService $userService)
    {
        // Assign the service instance to the class property
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::all();
        return new UserCollection($customers);
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

    /**
     * Display the specified resource.
     */
    public function show(User $admin)
    {
        //return new CustomerResource($customer);
        //return Response::json((new CustomerResource($customer)),'200');
        return response()->json([
            'code' => '200',
            'message' => 'success',
            'data' => $this->LoggedIn
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        //
    }

    public function something(){
        $data=[
            "code" => 200,
            "status" => "Success",
            "api_token" =>  self::$LoggedIn,
        ];
        return response()->json($data);
    }

    public function getToken(){
        $data=[
            "code" => 200,
            "status" => "Success",
            "api_token" =>  $this->userService->getToken(),
        ];
        return response()->json($data);
    }

    public function checkToken(Request $request){
        $token = $request->bearerToken();
        $checkToken = Token::where("token",'=', $token)->first();
        if ( $checkToken != null){
            $data=[
                "code" => 200,
                "status" => "Success",
                "token" => $token,
                "checkToken" =>  $checkToken
            ];
        }
        else{
            $data=[
                "code" => 422,
                "status" => "Failed",
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
    public function login(Request $request) {  
        
        $name=$request->input('name');
        $password=$request->input('password');        
        $model = User::where('name', '=', $name)->first();
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
                    $token->save();
                }
                $model->remember_token= $api_token;
                $model->save();
                $user=[
                    "name" => $model->name,
                ];
                $data=[
                    "code" => 200,
                    "status" => "Success",
                    "api_token" =>  $api_token,
                    "user" => $user
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
                "status" => "Email did not match.",
                "user" => null
            ];
        }        
        return response()->json($data);
      }
}
