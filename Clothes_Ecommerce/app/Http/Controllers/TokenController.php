<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Http\Requests\StoreTokenRequest;
use App\Http\Requests\UpdateTokenRequest;

class TokenController extends Controller
{
    protected UserService $userService;

    // Dependencies injection
    public function __construct( UserService $userService){
        $this-> userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreTokenRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Token $token)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Token $token)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTokenRequest $request, Token $token)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Token $token)
    {
        //
    }
}
