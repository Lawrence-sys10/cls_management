<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChiefRequest extends FormRequest
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
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('chiefs')->ignore($this->route('chief'))
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('chiefs')->ignore($this->route('chief'))
            ],
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
        ];
    }
}
