<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'plot_number' => 'required|string|max:50|unique:lands,plot_number',
            'area_acres' => 'required|numeric|min:0.01',
            'area_hectares' => 'required|numeric|min:0.01',
            'location' => 'required|string|max:255',
            'boundary_description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'polygon_boundaries' => 'nullable|json',
            'ownership_status' => 'required|in:vacant,allocated,under_dispute,reserved',
            'chief_id' => 'required|exists:chiefs,id',
            'price' => 'nullable|numeric|min:0',
            'land_use' => 'required|in:residential,commercial,agricultural,industrial,mixed',
            'soil_type' => 'nullable|string|max:100',
            'topography' => 'nullable|string|max:100',
            'access_roads' => 'nullable|array',
            'utilities' => 'nullable|array',
            'registration_date' => 'required|date',
            'is_verified' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'plot_number.unique' => 'This plot number already exists in the system.',
            'chief_id.exists' => 'The selected chief does not exist.',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
        ];
    }
}
