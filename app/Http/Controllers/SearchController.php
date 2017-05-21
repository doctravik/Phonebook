<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use App\Transformers\ContactTransformer;

class SearchController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');        
    }

    /**
     * Find contacts by name.
     * 
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($name)
    {
        $contacts = auth()->user()->contacts()->findLikeName($name)->get();

        return fractal()
            ->collection($contacts)
            ->transformWith(new ContactTransformer)
            ->toArray();    
    }
}
