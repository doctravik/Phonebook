<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;

class ContactController extends Controller
{
    /**
     * Get all contacts from db.
     * 
     * @return json
     */
    public function index()
    {
        return Contact::all(); 
    }

    /**
     * Store contact in db.
     *
     * @param StoreContactRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create(['name' => request('name')]);

        if ($contact && $request->exists('phone_number')) {
            $contact->addPhone(request('phone_number'));
        }

        return response()->json([], 200);
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
        $contact->delete();

        return response()->json([], 200);
    }
}