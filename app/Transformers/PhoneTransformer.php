<?php

namespace App\Transformers;

use App\Phone;
use League\Fractal\TransformerAbstract;

class PhoneTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'contact'
    ];

    public function transform(Phone $phone) {
        return [
            'id' => (int) $phone->id,
            'phone_number' => $phone->phone_name
        ];
    }

    /**
     * @return League\Fractal\Resource\Item
     */
    public function includeContact(Phone $phone) {
        return $this->item($phone->contact, new ContactTransformer);
    }   
}