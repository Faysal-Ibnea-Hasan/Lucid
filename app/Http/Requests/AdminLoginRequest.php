<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException; // Import needed for handling validation exceptions
use Illuminate\Contracts\Validation\Validator; // Import needed for validation

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool - Always returns true for this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> - Array of validation rules.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email', // Email field is required and must be a valid email format
            'password' => 'required', // Password field is required
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator - The validator instance containing the validation errors.
     * @throws HttpResponseException - Throws an exception with a JSON response containing the validation errors.
     */
    protected function failedValidation(Validator $validator)
    {
        // Throw an HttpResponseException with the validation errors and a 422 Unprocessable Entity status code
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
