@extends(''layouts.app'')

@section(''title'', ''Client Details - '' . $client->full_name)
@section(''header'', ''Client Details: '' . $client->full_name)

@section(''actions'')
<div class="flex space-x-2">
    <a href="{{ route(''clients.edit'', $client) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-edit mr-2"></i>Edit
    </a>
    <a href="{{ route(''allocations.create'') }}?client_id={{ $client->id }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-handshake mr-2"></i>Allocate Land
    </a>
</div>
@endsection

@section(''content'')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Client Information -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Information</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->phone }}</dd>
                    </div>
                    @if($client->email)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->email }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Occupation</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->occupation }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ str_replace(''_'', '' '', $client->id_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->id_number }}</dd>
                    </div>
                    @if($client->date_of_birth)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->date_of_birth->format(''M d, Y'') }}</dd>
                    </div>
                    @endif
                    @if($client->gender)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $client->gender }}</dd>
                    </div>
                    @endif
                </dl>

                <!-- Address -->
                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->address }}</dd>
                </div>

                <!-- Emergency Contact -->
                @if($client->emergency_contact)
                <div class="mt-4">
                    <dt class="text-sm font-medium text-gray-500">Emergency Contact</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->emergency_contact }}</dd>
                </div>
                @endif
            </div>
        </div>

        <!-- Land Allocations -->
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Land Allocations</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($client->allocations->count() > 0)
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plot Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocation Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($client->allocations as $allocation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $allocation->land->plot_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $allocation->land->location }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $allocation->allocation_date->format(''M d, Y'') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $allocation->approval_status == ''approved'' ? ''bg-green-100 text-green-800'' : 
                                           ($allocation->approval_status == ''pending'' ? ''bg-yellow-100 text-yellow-800'' : ''bg-red-100 text-red-800'') }}">
                                        {{ ucfirst($allocation->approval_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route(''allocations.show'', $allocation) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-gray-500 text-center py-4">No land allocations found for this client.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Documents and Quick Actions -->
    <div class="space-y-6">
        <!-- Documents -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Documents</h3>
                <button type="button" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-1"></i> Upload
                </button>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($client->documents->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($client->documents as $document)
                    <li class="py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $document->file_name }}</p>
                                    <p class="text-sm text-gray-500 capitalize">{{ str_replace(''_'', '' '', $document->document_type) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($document->is_verified)
                                <i class="fas fa-check-circle text-green-500" title="Verified"></i>
                                @endif
                                <a href="{{ route(''documents.download'', $document) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-gray-500 text-center py-4">No documents uploaded.</p>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Allocation Summary</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Total Allocations</dt>
                        <dd class="text-sm text-gray-900">{{ $client->allocations->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Approved</dt>
                        <dd class="text-sm text-green-600">{{ $client->allocations->where(''approval_status'', ''approved'')->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Pending</dt>
                        <dd class="text-sm text-yellow-600">{{ $client->allocations->where(''approval_status'', ''pending'')->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
