<?php

namespace App\Http\Controllers;

use App\Phone;
use App\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\PhoneRequest;

class PhoneController extends Controller
{
    /**
     * Store $phone in db.
     *
     * @param PhoneRequest $request
     * @param  Contact $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PhoneRequest $request, Contact $contact)
    {
        $contact->addPhone(request('phone_number'));

        return response()->json([], 200);
    }

    /**
     * Update $phone in db.
     *
     * @param PhoneRequest $request
     * @param  Phone  $phone
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PhoneRequest $request, Phone $phone)
    {
        if (request()->exists('phone_number')) {
            $phone->update(['phone_number' => request('phone_number')]);
        }

        return response()->json([], 200);
    }

    /**
     * Delete $phone from db.
     *
     * @param  Phone  $phone
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Phone $phone)
    {
        $phone->delete();

        return response()->json([], 200);
    }
}
