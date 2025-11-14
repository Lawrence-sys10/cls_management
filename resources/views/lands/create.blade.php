@extends('layouts.app')

@section('title', $land->exists ? 'Edit Land' : 'Add New Land')
@section('header', $land->exists ? 'Edit Land: ' . $land->plot_number : 'Add New Land')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ $land->exists ? route('lands.update', $land) : route('lands.store') }}">
                @csrf
                @if($land->exists)
                @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Plot Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Plot Information</h3>
                        
                        <div>
                            <label for="plot_number" class="block text-sm font-medium text-gray-700">Plot Number *</label>
                            <input type="text" name="plot_number" id="plot_number" value="{{ old('plot_number', $land->plot_number) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('plot_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $land->location) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="area_acres" class="block text-sm font-medium text-gray-700">Area (Acres) *</label>
                                <input type="number" step="0.01" name="area_acres" id="area_acres" value="{{ old('area_acres', $land->area_acres) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('area_acres')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="area_hectares" class="block text-sm font-medium text-gray-700">Area (Hectares) *</label>
                                <input type="number" step="0.01" name="area_hectares" id="area_hectares" value="{{ old('area_hectares', $land->area_hectares) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('area_hectares')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="chief_id" class="block text-sm font-medium text-gray-700">Chief *</label>
                            <select name="chief_id" id="chief_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select Chief</option>
                                @foreach($chiefs as $chief)
                                <option value="{{ $chief->id }}" {{ old('chief_id', $land->chief_id) == $chief->id ? 'selected' : '' }}>
                                    {{ $chief->name }} - {{ $chief->jurisdiction }}
                                </option>
                                @endforeach
                            </select>
                            @error('chief_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Land Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Land Details</h3>
                        
                        <div>
                            <label for="ownership_status" class="block text-sm font-medium text-gray-700">Ownership Status *</label>
                            <select name="ownership_status" id="ownership_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="vacant" {{ old('ownership_status', $land->ownership_status) == 'vacant' ? 'selected' : '' }}>Vacant</option>
                                <option value="allocated" {{ old('ownership_status', $land->ownership_status) == 'allocated' ? 'selected' : '' }}>Allocated</option>
                                <option value="under_dispute" {{ old('ownership_status', $land->ownership_status) == 'under_dispute' ? 'selected' : '' }}>Under Dispute</option>
                                <option value="reserved" {{ old('ownership_status', $land->ownership_status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            </select>
                            @error('ownership_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="land_use" class="block text-sm font-medium text-gray-700">Land Use *</label>
                            <select name="land_use" id="land_use" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="residential" {{ old('land_use', $land->land_use) == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('land_use', $land->land_use) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="agricultural" {{ old('land_use', $land->land_use) == 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                                <option value="industrial" {{ old('land_use', $land->land_use) == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                <option value="mixed" {{ old('land_use', $land->land_use) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                            @error('land_use')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="number" step="0.00000001" name="latitude" id="latitude" value="{{ old('latitude', $land->latitude) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="number" step="0.00000001" name="longitude" id="longitude" value="{{ old('longitude', $land->longitude) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (GHS)</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $land->price) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="boundary_description" class="block text-sm font-medium text-gray-700">Boundary Description</label>
                            <textarea name="boundary_description" id="boundary_description" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('boundary_description', $land->boundary_description) }}</textarea>
                        </div>
                        <div>
                            <label for="registration_date" class="block text-sm font-medium text-gray-700">Registration Date *</label>
                            <input type="date" name="registration_date" id="registration_date" value="{{ old('registration_date', $land->registration_date?->format('Y-m-d')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('registration_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('lands.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ $land->exists ? 'Update Land' : 'Create Land' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
