<?php

namespace App\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required',
            'email' => [
                'sometimes',
                'required',
                Rule::unique('users', 'email')->ignore($this->input('id')),
            ],
            'role_id' => 'sometimes|required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Login email is required.',
            'email.unique' => 'There is already another account with the same email.',
            'role_id.required' => 'Please assign a role to user.',
        ];
    }
}
