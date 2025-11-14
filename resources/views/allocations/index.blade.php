@extends(''layouts.app'')

@section(''title'', ''Allocation Management'')
@section(''header'', ''Allocation Management'')

@section(''actions'')
<a href="{{ route(''allocations.create'') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
    <i class="fas fa-plus mr-2"></i>New Allocation
</a>
@endsection

@section(''content'')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <!-- Filters -->
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request(''status'') == ''pending'' ? ''selected'' : '' }}>Pending</option>
                        <option value="approved" {{ request(''status'') == ''approved'' ? ''selected'' : '' }}>Approved</option>
                        <option value="rejected" {{ request(''status'') == ''rejected'' ? ''selected'' : '' }}>Rejected</option>
                        <option value="finalized" {{ request(''status'') == ''finalized'' ? ''selected'' : '' }}>Finalized</option>
                    </select>
                </div>
                <div>
                    <label for="chief_id" class="block text-sm font-medium text-gray-700">Chief</label>
                    <select name="chief_id" id="chief_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">All Chiefs</option>
                        @foreach($chiefs as $chief)
                        <option value="{{ $chief->id }}" {{ request(''chief_id'') == $chief->id ? ''selected'' : '' }}>
                            {{ $chief->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Allocations Table -->
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200" id="allocationsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocation Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Land Information</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Information</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($allocations as $allocation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $allocation->id }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->allocation_date->format(''M d, Y'') }}</div>
                            <div class="text-sm text-gray-500">By: {{ $allocation->processedBy->user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $allocation->land->plot_number }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->land->location }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->chief->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $allocation->client->full_name }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->client->phone }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->client->id_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $allocation->approval_status == ''approved'' ? ''bg-green-100 text-green-800'' : 
                                   ($allocation->approval_status == ''pending'' ? ''bg-yellow-100 text-yellow-800'' : 
                                   ($allocation->approval_status == ''rejected'' ? ''bg-red-100 text-red-800'' : ''bg-blue-100 text-blue-800'')) }}">
                                {{ ucfirst($allocation->approval_status) }}
                            </span>
                            @if($allocation->payment_status != ''paid'')
                            <div class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    {{ ucfirst($allocation->payment_status) }}
                                </span>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route(''allocations.show'', $allocation) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($allocation->approval_status == ''pending'')
                            <form action="{{ route(''allocations.approve'', $allocation) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3" onclick="return confirm(''Approve this allocation?'')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route(''allocations.reject'', $allocation) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm(''Reject this allocation?'')">
                                    <i class="fas fa-times"></i>
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
            {{ $allocations->links() }}
        </div>
    </div>
</div>
@endsection

@push(''scripts'')
<script>
    $(document).ready(function() {
        $(''#allocationsTable'').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: []
        });
    });
</script>
@endpush
