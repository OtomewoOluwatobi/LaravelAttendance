<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function create(Request $req)
    {
        $gender = \App\Models\User::listGender(false);
        return response()->json([
            "msg" => 'welcome to laravel docker api',
            "gender" => $gender
        ], Response::HTTP_OK);
    }
}
