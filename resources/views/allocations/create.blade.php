@extends(''layouts.app'')

@section(''title'', ''Create New Allocation'')
@section(''header'', ''Create New Allocation'')

@section(''content'')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ route(''allocations.store'') }}">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Land Selection -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Land Selection</h3>
                        
                        <div>
                            <label for="land_id" class="block text-sm font-medium text-gray-700">Select Land Plot *</label>
                            <select name="land_id" id="land_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select Land Plot</option>
                                @foreach($lands as $land)
                                <option value="{{ $land->id }}" {{ old(''land_id'', request(''land_id'')) == $land->id ? ''selected'' : '' }}>
                                    {{ $land->plot_number }} - {{ $land->location }} ({{ number_format($land->area_acres, 2) }} acres)
                                </option>
                                @endforeach
                            </select>
                            @error(''land_id'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="land-details" class="hidden p-4 bg-gray-50 rounded-md">
                            <h4 class="text-sm font-medium text-gray-900">Land Details</h4>
                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                <p><strong>Chief:</strong> <span id="land-chief"></span></p>
                                <p><strong>Price:</strong> GHS <span id="land-price"></span></p>
                                <p><strong>Land Use:</strong> <span id="land-use"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Client Selection -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Client Selection</h3>
                        
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Select Client *</label>
                            <select name="client_id" id="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old(''client_id'', request(''client_id'')) == $client->id ? ''selected'' : '' }}>
                                    {{ $client->full_name }} - {{ $client->phone }} ({{ $client->id_number }})
                                </option>
                                @endforeach
                            </select>
                            @error(''client_id'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="client-details" class="hidden p-4 bg-gray-50 rounded-md">
                            <h4 class="text-sm font-medium text-gray-900">Client Details</h4>
                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                <p><strong>Occupation:</strong> <span id="client-occupation"></span></p>
                                <p><strong>ID Type:</strong> <span id="client-id-type"></span></p>
                                <p><strong>Address:</strong> <span id="client-address"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Allocation Details -->
                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Allocation Details</h3>
                        
                        <div>
                            <label for="chief_id" class="block text-sm font-medium text-gray-700">Approving Chief *</label>
                            <select name="chief_id" id="chief_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select Chief</option>
                                @foreach($chiefs as $chief)
                                <option value="{{ $chief->id }}" {{ old(''chief_id'') == $chief->id ? ''selected'' : '' }}>
                                    {{ $chief->name }} - {{ $chief->jurisdiction }}
                                </option>
                                @endforeach
                            </select>
                            @error(''chief_id'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="allocation_date" class="block text-sm font-medium text-gray-700">Allocation Date *</label>
                            <input type="date" name="allocation_date" id="allocation_date" value="{{ old(''allocation_date'', now()->format(''Y-m-d'')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error(''allocation_date'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Payment Information</h3>
                        
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status *</label>
                            <select name="payment_status" id="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="pending" {{ old(''payment_status'') == ''pending'' ? ''selected'' : '' }}>Pending</option>
                                <option value="partial" {{ old(''payment_status'') == ''partial'' ? ''selected'' : '' }}>Partial Payment</option>
                                <option value="paid" {{ old(''payment_status'') == ''paid'' ? ''selected'' : '' }}>Paid</option>
                                <option value="overdue" {{ old(''payment_status'') == ''overdue'' ? ''selected'' : '' }}>Overdue</option>
                            </select>
                            @error(''payment_status'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_amount" class="block text-sm font-medium text-gray-700">Payment Amount (GHS)</label>
                            <input type="number" step="0.01" name="payment_amount" id="payment_amount" value="{{ old(''payment_amount'') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error(''payment_amount'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="processed_by" class="block text-sm font-medium text-gray-700">Processed By *</label>
                            <select name="processed_by" id="processed_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select Staff</option>
                                @foreach($staff as $staffMember)
                                <option value="{{ $staffMember->id }}" {{ old(''processed_by'') == $staffMember->id ? ''selected'' : '' }}>
                                    {{ $staffMember->user->name }} - {{ $staffMember->department }}
                                </option>
                                @endforeach
                            </select>
                            @error(''processed_by'')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old(''notes'') }}</textarea>
                    @error(''notes'')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route(''allocations.index'') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create Allocation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push(''scripts'')
<script>
    // Land selection details
    document.getElementById(''land_id'').addEventListener(''change'', function() {
        const landId = this.value;
        const landDetails = document.getElementById(''land-details'');
        
        if (landId) {
            // In a real application, you would fetch land details via AJAX
            // For now, we''ll simulate with the available data
            const selectedOption = this.options[this.selectedIndex];
            const landText = selectedOption.text;
            
            // Extract details from the option text (this is a simplified approach)
            document.getElementById(''land-chief'').textContent = ''Chief Name''; // You would get this from AJAX
            document.getElementById(''land-price'').textContent = ''0.00''; // You would get this from AJAX
            document.getElementById(''land-use'').textContent = ''Residential''; // You would get this from AJAX
            
            landDetails.classList.remove(''hidden'');
        } else {
            landDetails.classList.add(''hidden'');
        }
    });

    // Client selection details
    document.getElementById(''client_id'').addEventListener(''change'', function() {
        const clientId = this.value;
        const clientDetails = document.getElementById(''client-details'');
        
        if (clientId) {
            // Similar to land details, you would fetch client details via AJAX
            document.getElementById(''client-occupation'').textContent = ''Occupation'';
            document.getElementById(''client-id-type'').textContent = ''Ghana Card'';
            document.getElementById(''client-address'').textContent = ''Address'';
            
            clientDetails.classList.remove(''hidden'');
        } else {
            clientDetails.classList.add(''hidden'');
        }
    });
</script>
@endpush
