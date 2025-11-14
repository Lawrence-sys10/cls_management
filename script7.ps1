# Step 7: Generate Form Views, Reports, Admin Panels and Authentication
# Save this as generate-forms-admin.ps1 and run from project root

# Define paths
$viewsPath = "resources/views"
$clientsPath = "$viewsPath/clients"
$allocationsPath = "$viewsPath/allocations"
$reportsPath = "$viewsPath/reports"
$adminPath = "$viewsPath/admin/users"
$authPath = "$viewsPath/auth"

# Create directories if they don't exist
@($reportsPath, $adminPath, $authPath) | ForEach-Object {
    $parentDir = Split-Path $_ -Parent
    if (!(Test-Path $parentDir)) {
        New-Item -ItemType Directory -Path $parentDir -Force
    }
    if (!(Test-Path $_)) {
        New-Item -ItemType Directory -Path $_ -Force
    }
}

# 1. Client Create/Edit Form
@'
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
'@ | Out-File -FilePath "$clientsPath/create.blade.php" -Encoding UTF8

# 2. Allocation Create Form
@'
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
'@ | Out-File -FilePath "$allocationsPath/create.blade.php" -Encoding UTF8

# 3. Reports Index View
@'
@extends(''layouts.app'')

@section(''title'', ''Reports & Analytics'')
@section(''header'', ''Reports & Analytics'')

@section(''content'')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- Report Generation Cards -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Generate Reports</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="space-y-6">
                <!-- Lands Report -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Lands Report</h4>
                    <form method="POST" action="{{ route(''reports.lands.generate'') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="chief_id" class="block text-sm font-medium text-gray-700">Chief</label>
                                <select name="chief_id" id="chief_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">All Chiefs</option>
                                    @foreach(\App\Models\Chief::all() as $chief)
                                    <option value="{{ $chief->id }}">{{ $chief->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">All Status</option>
                                    <option value="vacant">Vacant</option>
                                    <option value="allocated">Allocated</option>
                                    <option value="under_dispute">Under Dispute</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-3">
                            <button type="submit" name="format" value="pdf" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-file-pdf mr-2"></i>PDF Report
                            </button>
                            <button type="submit" name="format" value="excel" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-file-excel mr-2"></i>Excel Report
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Allocations Report -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Allocations Report</h4>
                    <form method="POST" action="{{ route(''reports.allocations.generate'') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="alloc_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="alloc_start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="alloc_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="alloc_end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="alloc_status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="alloc_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-3">
                            <button type="submit" name="format" value="pdf" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-file-pdf mr-2"></i>PDF Report
                            </button>
                            <button type="submit" name="format" value="excel" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-file-excel mr-2"></i>Excel Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Generated Reports -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Reports</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            @if($reports->count() > 0)
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reports as $report)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $report->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                    {{ $report->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $report->generatedBy->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $report->created_at->format(''M d, Y'') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form action="{{ route(''reports.destroy'', $report) }}" method="POST" class="inline">
                                    @csrf
                                    @method(''DELETE'')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm(''Delete this report?'')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $reports->links() }}
            </div>
            @else
            <p class="text-sm text-gray-500 text-center py-8">No reports generated yet.</p>
            @endif
        </div>
    </div>
</div>

<!-- Analytics Overview -->
<div class="mt-6 bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Quick Analytics</h3>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Land::count() }}</div>
                <div class="text-sm text-gray-500">Total Lands</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ \App\Models\Land::where(''ownership_status'', ''allocated'')->count() }}</div>
                <div class="text-sm text-gray-500">Allocated Lands</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Client::count() }}</div>
                <div class="text-sm text-gray-500">Total Clients</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ \App\Models\Allocation::where(''approval_status'', ''pending'')->count() }}</div>
                <div class="text-sm text-gray-500">Pending Approvals</div>
            </div>
        </div>
    </div>
</div>
@endsection
'@ | Out-File -FilePath "$reportsPath/index.blade.php" -Encoding UTF8

# 4. Admin Users Index View
@'
@extends(''layouts.app'')

@section(''title'', ''User Management'')
@section(''header'', ''User Management'')

@section(''actions'')
<a href="{{ route(''admin.users.create'') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
    <i class="fas fa-plus mr-2"></i>Add User
</a>
@endsection

@section(''content'')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <!-- Users Table -->
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-medium">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->phone ?? ''N/A'' }}</div>
                            <div class="text-sm text-gray-500">{{ $user->user_type }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? ''bg-green-100 text-green-800'' : ''bg-red-100 text-red-800'' }}">
                                {{ $user->is_active ? ''Active'' : ''Inactive'' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : ''Never'' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route(''admin.users.edit'', $user) }}" class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route(''admin.users.destroy'', $user) }}" method="POST" class="inline">
                                @csrf
                                @method(''DELETE'')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm(''Are you sure you want to delete this user?'')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

@push(''scripts'')
<script>
    $(document).ready(function() {
        $(''#usersTable'').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: []
        });
    });
</script>
@endpush
'@ | Out-File -FilePath "$adminPath/index.blade.php" -Encoding UTF8

# 5. Authentication Views (Login)
@'
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <div class="w-20 h-20 rounded-full bg-green-600 flex items-center justify-center">
                    <i class="fas fa-landmark text-white text-2xl"></i>
                </div>
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session(''status'')" />

        <form method="POST" action="{{ route(''login'') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__(''Email'')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old(''email'')" required autofocus />
                <x-input-error :messages="$errors->get(''email'')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__(''Password'')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get(''password'')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __(''Remember me'') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                @if (Route::has(''password.request''))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route(''password.request'') }}">
                        {{ __(''Forgot your password?'') }}
                    </a>
                @endif

                <x-primary-button class="ml-3 bg-green-600 hover:bg-green-700">
                    {{ __(''Log in'') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
'@ | Out-File -FilePath "$authPath/login.blade.php" -Encoding UTF8

Write-Host "✅ Form views, reports, and admin panels generated successfully!" -ForegroundColor Green
Write-Host "📁 Files created:" -ForegroundColor Cyan
Write-Host "   - resources/views/clients/create.blade.php" -ForegroundColor White
Write-Host "   - resources/views/allocations/create.blade.php" -ForegroundColor White
Write-Host "   - resources/views/reports/index.blade.php" -ForegroundColor White
Write-Host "   - resources/views/admin/users/index.blade.php" -ForegroundColor White
Write-Host "   - resources/views/auth/login.blade.php" -ForegroundColor White
Write-Host "🚀 Next step: We'll create Excel exports, PDF reports, and additional components" -ForegroundColor Yellow