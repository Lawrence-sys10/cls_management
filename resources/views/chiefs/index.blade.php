@extends(''layouts.app'')

@section(''title'', ''Chief Management'')
@section(''header'', ''Chief Management'')

@section(''actions'')
@can(''admin'')
<a href="{{ route(''chiefs.create'') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
    <i class="fas fa-plus mr-2"></i>Add Chief
</a>
@endcan
@endsection

@section(''content'')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <!-- Search -->
        <form method="GET" class="mb-6">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request(''search'') }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="Search by name or jurisdiction...">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>

        <!-- Chiefs Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($chiefs as $chief)
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-crown text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $chief->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $chief->jurisdiction }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Lands</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $chief->lands_count }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Allocations</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $chief->allocations_count }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-phone mr-2"></i>{{ $chief->phone }}
                        </p>
                        @if($chief->email)
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-envelope mr-2"></i>{{ $chief->email }}
                        </p>
                        @endif
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $chief->is_active ? ''bg-green-100 text-green-800'' : ''bg-red-100 text-red-800'' }}">
                            {{ $chief->is_active ? ''Active'' : ''Inactive'' }}
                        </span>
                        <div class="flex space-x-2">
                            <a href="{{ route(''chiefs.show'', $chief) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can(''admin'')
                            <a href="{{ route(''chiefs.edit'', $chief) }}" class="text-green-600 hover:text-green-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $chiefs->links() }}
        </div>
    </div>
</div>
@endsection
