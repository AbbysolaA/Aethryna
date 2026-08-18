@extends('layouts.aethryna')

@section('title', 'Create Pathway | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
    <!-- Header -->
    <section class="bg-gradient-to-r from-teal-700 to-teal-900 text-white py-12 px-8">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">Create New Pathway</h1>
                <p class="text-lg opacity-90">Add a new career pathway to the platform</p>
            </div>
            <a href="{{ route('admin.content') }}" class="text-white hover:text-yellow-400 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Content
            </a>
        </div>
    </section>

    <!-- Form Section -->
    <section class="bg-gray-50 py-12 px-8">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-md p-8">
            <form action="{{ route('admin.content.pathway.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Pathway Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                        <select name="category" id="category" class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                            @foreach(['technical', 'creative', 'business', 'security', 'foundation'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Difficulty -->
                <div>
                     <label for="difficulty_level" class="block text-sm font-semibold text-gray-700 mb-1">Difficulty Level</label>
                     <select name="difficulty_level" id="difficulty_level" class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                        @foreach(['Beginner', 'Intermediate', 'Advanced'] as $level)
                            <option value="{{ $level }}" {{ old('difficulty_level') == $level ? 'selected' : '' }}>
                                {{ $level }}
                            </option>
                        @endforeach
                     </select>
                </div>

                <!-- Summary -->
                <div>
                    <label for="summary" class="block text-sm font-semibold text-gray-700 mb-1">Summary (Short Description)</label>
                    <textarea name="summary" id="summary" rows="3" class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200" required>{{ old('summary') }}</textarea>
                </div>

                <!-- Full Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Full Description</label>
                    <textarea name="description" id="description" rows="5" class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200" required>{{ old('description') }}</textarea>
                </div>

                <!-- Key Skills -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Key Skills</label>
                    <div id="skills-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="key_skills[]" class="flex-1 rounded-lg border-gray-300" placeholder="e.g. Photoshop" required>
                        </div>
                    </div>
                    <button type="button" onclick="addItem('skills-container', 'key_skills[]')" class="mt-2 text-sm text-teal-600 hover:text-teal-800 font-semibold">
                        + Add Skill
                    </button>
                </div>

                <!-- Potential Roles -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Potential Roles</label>
                    <div id="roles-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="potential_roles[]" class="flex-1 rounded-lg border-gray-300" placeholder="e.g. Graphic Designer" required>
                        </div>
                    </div>
                    <button type="button" onclick="addItem('roles-container', 'potential_roles[]')" class="mt-2 text-sm text-teal-600 hover:text-teal-800 font-semibold">
                        + Add Role
                    </button>
                </div>

                <!-- Learning Resources -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Learning Resources</label>
                    <div id="resources-container" class="space-y-2">
                         <div class="flex gap-2">
                            <input type="text" name="learning_resources[]" class="flex-1 rounded-lg border-gray-300" placeholder="e.g. Coursera UX Design Course" required>
                        </div>
                    </div>
                    <button type="button" onclick="addItem('resources-container', 'learning_resources[]')" class="mt-2 text-sm text-teal-600 hover:text-teal-800 font-semibold">
                        + Add Resource
                    </button>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-white px-6 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-1">
                        Create Pathway
                    </button>
                    <a href="{{ route('admin.content') }}" class="text-gray-500 hover:text-teal-700 font-semibold px-4 py-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
    <script>
        function addItem(containerId, inputName) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="${inputName}" class="flex-1 rounded-lg border-gray-300" required>
                <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(div);
        }
    </script>
    @endpush
@endsection
