<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Find users by name.
     * 
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($name)
    {
        $users = User::findLikeName($name)->get();

        return response()->json($users, 200);    
    }
}
