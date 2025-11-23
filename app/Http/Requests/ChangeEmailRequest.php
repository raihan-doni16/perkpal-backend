<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'current_password' => ['required', 'string'],
            'new_email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user ? $user->id : null)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Current password is required for security verification',
            'new_email.required' => 'New email is required',
            'new_email.email' => 'Please provide a valid email address',
            'new_email.unique' => 'This email is already taken',
        ];
    }
}
