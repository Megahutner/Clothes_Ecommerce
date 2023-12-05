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
}