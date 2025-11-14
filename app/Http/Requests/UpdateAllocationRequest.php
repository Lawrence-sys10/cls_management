<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'staff']);
    }

    public function rules(): array
    {
        $allocation = $this->route('allocation');
        
        return [
            'land_id' => [
                'required',
                'exists:lands,id',
                Rule::unique('allocations')->ignore($allocation->id)->where(function ($query) {
                    return $query->where('approval_status', '!=', 'rejected');
                })
            ],
            'client_id' => 'required|exists:clients,id',
            'chief_id' => 'required|exists:chiefs,id',
            'processed_by' => 'required|exists:staff,id',
            'allocation_date' => 'required|date',
            'approval_status' => 'required|in:pending,approved,rejected,finalized',
            'payment_status' => 'required|in:pending,partial,paid,overdue',
            'payment_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'land_id.unique' => 'This land plot is already allocated to another client.',
            'land_id.exists' => 'The selected land plot does not exist.',
        ];
    }
}
