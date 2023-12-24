<?php
namespace App\Services;

use App\Models\User;
use App\Models\Token;

class UserService
{
    // Declare the function as static
    public static function getToken()
    {
        return Token::all();
    }

    public static function removeTokenDaily(){
        Token::where("expires_at","<", Carbon::now())->delete();
    }

    public static function checkAuthen($token){
        $checkToken = Token::where("token",'=', $token)->first();
        if($checkToken != null){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
}