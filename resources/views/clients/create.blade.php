@extends(''layouts.app'')

@section(''title'', $client->exists ? ''Edit Client'' : ''Add New Client'')
@section(''header'', $client->exists ? ''Edit Client: '' . $client->full_name : ''Add New Client'')

@section(''content'')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ $client->exists ? route(''clients.update'', $client) : route(''clients.store'') }}">
                @csrf
                @if($client->exists)
                @method(''PUT'')
                @endif

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                        
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old(''full_name'', $client->full_name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error(''full_name'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                            <input type="text" name="phone" id="phone" value="{{ old(''phone'', $client->phone) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error(''phone'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old(''email'', $client->email) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error(''email'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="id_type" class="block text-sm font-medium text-gray-700">ID Type *</label>
                                <select name="id_type" id="id_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="ghanacard" {{ old(''id_type'', $client->id_type) == ''ghanacard'' ? ''selected'' : '' }}>Ghana Card</option>
                                    <option value="passport" {{ old(''id_type'', $client->id_type) == ''passport'' ? ''selected'' : '' }}>Passport</option>
                                    <option value="drivers_license" {{ old(''id_type'', $client->id_type) == ''drivers_license'' ? ''selected'' : '' }}>Driver''s License</option>
                                    <option value="voters_id" {{ old(''id_type'', $client->id_type) == ''voters_id'' ? ''selected'' : '' }}>Voter''s ID</option>
                                </select>
                                @error(''id_type'')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="id_number" class="block text-sm font-medium text-gray-700">ID Number *</label>
                                <input type="text" name="id_number" id="id_number" value="{{ old(''id_number'', $client->id_number) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error(''id_number'')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
                        
                        <div>
                            <label for="occupation" class="block text-sm font-medium text-gray-700">Occupation *</label>
                            <input type="text" name="occupation" id="occupation" value="{{ old(''occupation'', $client->occupation) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error(''occupation'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old(''date_of_birth'', $client->date_of_birth?->format(''Y-m-d'')) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error(''date_of_birth'')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old(''gender'', $client->gender) == ''male'' ? ''selected'' : '' }}>Male</option>
                                    <option value="female" {{ old(''gender'', $client->gender) == ''female'' ? ''selected'' : '' }}>Female</option>
                                    <option value="other" {{ old(''gender'', $client->gender) == ''other'' ? ''selected'' : '' }}>Other</option>
                                </select>
                                @error(''gender'')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old(''emergency_contact'', $client->emergency_contact) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error(''emergency_contact'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Full Address *</label>
                        <textarea name="address" id="address" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old(''address'', $client->address) }}</textarea>
                        @error(''address'')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route(''clients.index'') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ $client->exists ? ''Update Client'' : ''Create Client'' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
