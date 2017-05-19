<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Get all users from db.
     * 
     * @return json
     */
    public function index()
    {
        return User::all(); 
    }

    /**
     * Store user in db.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        User::create(['name' => request('name')]);

        return response()->json([], 200);
    }
}
