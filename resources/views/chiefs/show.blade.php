<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chief Details - {{ $chief->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Chief Details</h1>
                    <p class="text-gray-600">View chief information and allocations</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('chiefs.edit', $chief) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Edit Chief
                    </a>
                    <a href="{{ route('chiefs.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        Back to Chiefs
                    </a>
                </div>
            </div>
        </div>

        <!-- Chief Information Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Chief Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $chief->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jurisdiction</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $chief->jurisdiction }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $chief->phone ?? 'Not provided' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $chief->email ?? 'Not provided' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $chief->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $chief->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Registered Date</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $chief->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            
            @if($chief->area_boundaries)
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Area Boundaries</label>
                <p class="mt-1 text-gray-900">{{ $chief->area_boundaries }}</p>
            </div>
            @endif
        </div>

        <!-- Statistics Card -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_allocations'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Total Allocations</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved_allocations'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Approved</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_allocations'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Pending</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected_allocations'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Rejected</div>
            </div>
        </div>

        <!-- Allocations Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Recent Allocations</h2>
                <a href="{{ route('allocations.index', ['chief_id' => $chief->id]) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View All Allocations
                </a>
            </div>

            @if($allocations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Client
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Land
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($allocations as $allocation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $allocation->client->full_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $allocation->land->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $allocation->land->plot_number ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $allocation->approval_status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($allocation->approval_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($allocation->approval_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $allocation->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('allocations.show', $allocation) }}" 
                                           class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $allocations->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No allocations found for this chief.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>