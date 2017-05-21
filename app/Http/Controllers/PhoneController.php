<?php

namespace App\Http\Controllers;

use App\Phone;
use App\Contact;
use Illuminate\Http\Request;
use App\Transformers\PhoneTransformer;
use App\Http\Requests\StorePhoneRequest;
use App\Http\Requests\UpdatePhoneRequest;

class PhoneController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');        
    }

    /**
     * Store $phone in db.
     *
     * @param StorePhoneRequest $request
     * @param  Contact $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePhoneRequest $request, Contact $contact)
    {
        $this->authorize('update', $contact);

        $phone = $contact->addPhone([
            'phone_number' => request('phone_number'),
            'user_id' => auth()->id()
        ]);

        return fractal()
            ->item($phone)
            ->transformWith(new PhoneTransformer)
            ->toArray();
    }

    /**
     * Update $phone in db.
     *
     * @param UpdatePhoneRequest $request
     * @param  Phone  $phone
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePhoneRequest $request, Phone $phone)
    {
        $this->authorize('update', $phone);

        $phone->update(['phone_number' => request('phone_number')]);

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
        $this->authorize('delete', $phone);

        $phone->delete();

        return response()->json([], 200);
    }
}
