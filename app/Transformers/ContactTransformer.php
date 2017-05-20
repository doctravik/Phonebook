<?php

namespace App\Transformers;

use App\Contact;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'phones', 
    ];

    /**
     * Turn this Contact object into a generic array.
     *
     * @return array
     */
    public function transform(Contact $contact) {
        return [
            'id' => (int) $contact->id,
            'name' => $contact->name
        ];
    }

    /**
     * @return League\Fractal\Resource\Collection
     */
    public function includePhones(Contact $contact) {
        return $this->collection($contact->phones, new PhoneTransformer);
    }   
}