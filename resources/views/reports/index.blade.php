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
