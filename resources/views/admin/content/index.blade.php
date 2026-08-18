@extends('layouts.aethryna')

@section('title', 'Content Management | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
    <!-- Header -->
    <section class="bg-gradient-to-r from-teal-700 to-teal-900 text-white py-16 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400 opacity-10 rounded-full -mr-48 -mt-48"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">Content Management</h1>
                <p class="text-lg opacity-90">Manage pathways and assessment questions</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-teal-800 px-6 py-3 rounded-full font-bold hover:bg-teal-50 transition-colors shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </section>

    <!-- Content Sections -->
    <section class="bg-gray-50 py-12 px-8">
        <div class="max-w-6xl mx-auto grid gap-12">
            
            <!-- Pathways Section -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-teal-700">Pathways</h3>
                    <a href="{{ route('admin.content.pathway.create') }}" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                        <i class="fas fa-plus mr-1"></i> Add Pathway
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @forelse($pathways as $pathway)
                        <div class="border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-shadow bg-white flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-lg text-gray-800 mb-2">{{ $pathway->name }}</h4>
                                <p class="text-sm text-gray-500 mb-4 line-clamp-3">{{ $pathway->description }}</p>
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded inline-block mb-3">
                                    {{ $pathway->category }}
                                </span>
                                <span class="ml-2 bg-purple-100 text-purple-800 text-xs font-bold px-2 py-1 rounded inline-block mb-3" title="Total users recommended this pathway">
                                    <i class="fas fa-users mr-1"></i> {{ $pathway->assessment_results_count }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                                <span class="text-xs text-gray-400">ID: {{ $pathway->id }}</span>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.content.pathway.edit', $pathway->id) }}" class="text-gray-400 hover:text-teal-600">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.content.pathway.destroy', $pathway->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this pathway?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            No pathways available.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Questions Section -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-teal-700">Assessment Questions</h3>
                    <a href="{{ route('admin.content.question.create') }}" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                        <i class="fas fa-plus mr-1"></i> Add Question
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                <th class="p-4 border-b border-gray-200 font-semibold w-16">#</th>
                                <th class="p-4 border-b border-gray-200 font-semibold">Question Text</th>
                                <th class="p-4 border-b border-gray-200 font-semibold text-center w-24">Answers</th>
                                <th class="p-4 border-b border-gray-200 font-semibold text-right w-32">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse($questions as $question)
                                <tr class="hover:bg-teal-50 transition-colors border-b border-gray-100 last:border-0">
                                    <td class="p-4 font-bold text-teal-600">{{ $question->question_number }}</td>
                                    <td class="p-4 text-gray-800 font-medium">{{ $question->text }}</td>
                                    <td class="p-4 text-center">
                                        <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2 py-1 rounded-full">
                                            {{ $question->answers->count() }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.content.question.edit', $question->id) }}" class="text-gray-400 hover:text-teal-600 transition-colors mx-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.content.question.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors mx-1">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500">
                                        No questions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
@endsection
