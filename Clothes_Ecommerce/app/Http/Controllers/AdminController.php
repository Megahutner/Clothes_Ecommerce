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
use Carbon\Carbon;



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
        $users = User::all();
        return new UserCollection($users);
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

     public function rootAccount(){
        $user = new User();
        $user-> name = "root";
        $user-> password = Hash::make('password');
        $user->email = "root2024@yopmail.com";
        $user->type = 0;
        $user-> save();
        $data=[
             "code" => 200,
             "message" => "Success",
             "user" => new UserResource($user)
         ];
         return response()->json($data);
     }

     public function store(StoreAdminRequest $request) // register API
     {
        $token = $request->bearerToken();
        //$checkToken = Token::where("token",'=', $token)->first();
        $checkToken = $userService->checkAuthen($token);
        if(!checkToken){
            $data=[
                "code" => 400,
                "message" => "Unauthorized",
            ];
        }
        else{
            $user = new User();
            $input = $request->all();
            $user->name = $input['name'];
            $user->password = Hash::make($input['password']);
            $user->email = $input['email'];
            $user->type = $input['type'];
            $user-> save();
            $data=[
                "code" => 200,
                "message" => "Success",
                "user" => new UserResource($user)
            ];
        }
         return response()->json($data);
     }

      /**
     * ChangePassowrd.
     */

     public function resetPassword(Request $request) // admin reset API
     {
        $input = $request->all();
         $user =  User::find($input['id']);
         if($user == null){
            return response()->json([
                'code' => 422,
                'message' => 'Non-exist admin',
            ]);
         }
         $newPass = $input['newPass'];
         $recheckPass = $input['recheckPass'];
         if($newPass != $recheckPass){
            return response()->json([
                'code' => 422,
                'message' => 'Unmatched',
            ]);
         }
         $user->password = Hash::make($newPass);
         $user-> save();
         $data=[
             "code" => 200,
             "message" => "Success",
             "user" => new UserResource($user)
         ];
         return response()->json($data);
     }

    /**
     * Display the specified resource.
     */

     public function show($id)
     {
         $user = User::find($id);
         if($user == null){
           return response()->json([
               'code' => 422,
               'message' => 'Non-exist admin',
           ]);
         }
         return response()->json([
             'code' => 200,
             'message' => 'success',
             'data' => new UserResource($user)
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
    public function destroy($id)
    {
        $user = User::find($id);
        if($user == null){
          return response()->json([
              'code' => 422,
              'message' => 'Non-exist staff',
          ]);
        }

        if($user == null){
            return response()->json([
                'code' => 422,
                'message' => 'Non-exist staff',
            ]);
          }

        $user->delete();
        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
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
        //$checkToken = Token::where("token",'=', $token)->first();
        $checkToken = $userService->checkAuthen($token);
        if ($checkToken){
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
                "status" => "Unauthorized",
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
                    $token->expires_at = Carbon::now()->addDays(1) ;
                    $token->save();
                }
                $model->remember_token= $api_token;
                $model->save();
                $user=[
                    "name" => $model->name,
                    "type"=> $model->type,
                ];
                $data=[
                    "code" => 200,
                    "message" => "Success",
                    "api_token" =>  $api_token,
                    "user" => $user
                ];
            } else {
                $data=[
                    "code" => 401,
                    "message" => "Password did not match.",
                    "user" => null
                ];
            }
        } 
        if(empty($model)) {
            $data=[
                "code" => 402,
                "message" => "An error has occured",
                "user" => null
            ];
        }        
        return response()->json($data);
      }
}
