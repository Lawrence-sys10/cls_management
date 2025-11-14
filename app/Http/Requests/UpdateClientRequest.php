<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Client;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'staff']);
    }

    public function rules(): array
    {
        $client = $this->route('client');

        return [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:clients,phone,' . $client->id,
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'id_type' => 'required|in:ghanacard,passport,drivers_license,voters_id',
            'id_number' => 'required|string|max:50|unique:clients,id_number,' . $client->id,
            'address' => 'required|string|max:500',
            'occupation' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact' => 'nullable|string|max:15',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'The full name field is required.',
            'phone.required' => 'The phone number field is required.',
            'phone.unique' => 'This phone number is already registered.',
            'phone.max' => 'The phone number must not exceed 15 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'id_type.required' => 'Please select an ID type.',
            'id_type.in' => 'Please select a valid ID type.',
            'id_number.required' => 'The ID number field is required.',
            'id_number.unique' => 'This ID number is already registered.',
            'id_number.max' => 'The ID number must not exceed 50 characters.',
            'address.required' => 'The address field is required.',
            'address.max' => 'The address must not exceed 500 characters.',
            'occupation.required' => 'The occupation field is required.',
            'occupation.max' => 'The occupation must not exceed 255 characters.',
            'date_of_birth.date' => 'Please enter a valid date of birth.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'gender.in' => 'Please select a valid gender.',
            'emergency_contact.max' => 'The emergency contact must not exceed 15 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'full name',
            'id_number' => 'ID number',
            'id_type' => 'ID type',
            'emergency_contact' => 'emergency contact',
            'date_of_birth' => 'date of birth',
        ];
    }
}