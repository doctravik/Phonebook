<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use App\Transformers\ContactTransformer;

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

        return fractal()
            ->collection($contacts)
            ->transformWith(new ContactTransformer)
            ->toArray();    
    }
}
