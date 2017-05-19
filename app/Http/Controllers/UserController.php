<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

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
}
