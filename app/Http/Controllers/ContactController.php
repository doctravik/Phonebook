<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use App\Transformers\ContactTransformer;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;

class ContactController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');        
    }

    /**
     * Get all contacts from db.
     * 
     * @return json
     */
    public function index()
    {
        $contacts = auth()->user()->contacts()->with('phones')->get();

        return fractal()
            ->collection($contacts)
            ->transformWith(new ContactTransformer)
            ->includePhones()
            ->toArray(); 
    }

    /**
     * Store contact in db.
     *
     * @param StoreContactRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreContactRequest $request)
    {
        $contact = auth()->user()->addContact(['name' => request('name')]);
        
        $contact->addPhone([
            'user_id' => auth()->id(),
            'phone_number' => request('phone_number')
        ]);

        return fractal()
            ->item($contact->load('phones'))
            ->transformWith(new ContactTransformer)
            ->includePhones()
            ->toArray();
    }

    /**
     * Update contact in db.
     *
     * @param UpdateContactRequest $request
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $this->authorize('update', $contact);

        $contact->update(['name' => request('name')]);

        return response()->json([], 200);
    }

    /**
     * Delete contact from db.
     * 
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();

        return response()->json([], 200);
    }
}
