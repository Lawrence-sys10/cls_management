<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChiefRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'jurisdiction' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:chiefs,phone',
            'email' => 'nullable|email|unique:chiefs,email',
            'area_boundaries' => 'nullable|json',
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'This phone number is already registered for another chief.',
            'email.unique' => 'This email address is already registered.',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }
}
