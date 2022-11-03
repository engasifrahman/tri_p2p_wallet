<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'currency' => 'required|string|max:3|in:USD,EUR',
            'password' => 'required|string|min:8|max:20|confirmed',
        ];
    }

    /**
     * @overrride
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator){
        if($this->wantsJson()){
            $errors = $validator->errors();
            $message = 'Validation failed!';
            response()->setResponse(false, $message, $errors, null, 422);

            throw new HttpResponseException(response()->getResponse());
        }else{
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}
