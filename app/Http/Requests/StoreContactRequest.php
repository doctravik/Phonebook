<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                Rule::unique('contacts')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],

            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('phones')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ]
        ];
    }
}
