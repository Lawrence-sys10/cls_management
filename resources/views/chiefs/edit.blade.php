<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Chief - {{ $chief->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('chiefs.show', $chief) }}" 
               class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← Back to Chief Details
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Chief</h1>
            <p class="text-gray-600">Editing: {{ $chief->name }}</p>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('chiefs.update', $chief) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name', $chief->name) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jurisdiction -->
                    <div>
                        <label for="jurisdiction" class="block text-sm font-medium text-gray-700">Jurisdiction *</label>
                        <input type="text" 
                               name="jurisdiction" 
                               id="jurisdiction"
                               value="{{ old('jurisdiction', $chief->jurisdiction) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('jurisdiction')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                        <input type="text" 
                               name="phone" 
                               id="phone"
                               value="{{ old('phone', $chief->phone) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ old('email', $chief->email) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Area Boundaries -->
                    <div class="md:col-span-2">
                        <label for="area_boundaries" class="block text-sm font-medium text-gray-700">Area Boundaries</label>
                        <textarea name="area_boundaries" 
                                  id="area_boundaries"
                                  rows="3"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">{{ old('area_boundaries', $chief->area_boundaries) }}</textarea>
                        @error('area_boundaries')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ $chief->is_active ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active Chief</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">Uncheck to deactivate this chief</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('chiefs.show', $chief) }}" 
                       class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Update Chief
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>