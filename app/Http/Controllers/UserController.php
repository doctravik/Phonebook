<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

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
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        User::create(['name' => request('name')]);

        return response()->json([], 200);
    }

    /**
     * Update user in db.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update(['name' => request('name')]);

        return response()->json([], 200);
    }

    /**
     * Delete user from db.
     * 
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([], 200);
    }
}
