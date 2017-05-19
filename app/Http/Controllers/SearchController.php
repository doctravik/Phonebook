<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Find contacts by name.
     * 
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($name)
    {
        $contacts = Contact::findLikeName($name)->get();

        return response()->json($contacts, 200);    
    }
}
