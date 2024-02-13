<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'email'      => ['required', 'email', Rule::unique('users', 'email')],
            'name'       => ['required'],
            'password'   => ['required'],
            'c_password' => ['required', 'same:password'],
        ];
    }

    /**
     * Messages error for each validation
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'      => 'Email is required',
            'email.email'         => 'Format email is invalid',
            'name.required'       => 'Name is required',
            'password.required'   => 'Password is required',
            'c_password.same'     => 'Confirm password does not match',
            'c_password.required' => 'Confirm password is required',
        ];
    }

	/**
     * This function throws an exception with a JSON response containing validation errors if
     * validation fails.
     * 
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => 'true',
            'message'  => $validator->errors()->all(),
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}