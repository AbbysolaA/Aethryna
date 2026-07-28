@extends('layouts.aethryna')

@section('title', 'Reports | Skills Co-op')

@section('content')
    <!-- Header -->
    <section class="bg-gradient-to-r from-teal-700 to-teal-900 text-white py-16 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400 opacity-10 rounded-full -mr-48 -mt-48"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">Reports & Analytics</h1>
                <p class="text-lg opacity-90">Review assessment performance and user trends</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-teal-800 px-6 py-3 rounded-full font-bold hover:bg-teal-50 transition-colors shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </section>

    <!-- Assessment Reports -->
    <section class="bg-gray-50 py-12 px-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-teal-700">Recent Assessments</h3>
                    <span class="bg-teal-100 text-teal-800 text-xs font-bold px-3 py-1 rounded-full">Total: {{ $assessments->total() }}</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                <th class="p-4 border-b border-gray-200 font-semibold">User</th>
                                <th class="p-4 border-b border-gray-200 font-semibold">Pathway</th>
                                <th class="p-4 border-b border-gray-200 font-semibold text-center">Score</th>
                                <th class="p-4 border-b border-gray-200 font-semibold">Date</th>
                                <th class="p-4 border-b border-gray-200 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse($assessments as $assessment)
                                <tr class="hover:bg-teal-50 transition-colors border-b border-gray-100 last:border-0">
                                    <td class="p-4 font-medium text-teal-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center text-white text-sm font-bold">
                                                {{ $assessment->user ? substr($assessment->user->name, 0, 1) : 'G' }}
                                            </div>
                                            {{ $assessment->user->name ?? 'Guest User' }}
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm font-semibold text-blue-600">
                                        {{ $assessment->results->first()->pathway->name ?? 'N/A' }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full">
                                            {{ $assessment->results->first()->score ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-sm text-gray-500">{{ $assessment->created_at->format('M d, Y') }}</td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end">
                                            <a href="#" class="text-gray-400 hover:text-teal-600 transition-colors mx-1" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="text-gray-400 hover:text-red-500 transition-colors mx-1" title="Delete" onclick="return confirm('To delete an assessment, please use the database management tools or contact support.');">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500">
                                        No assessments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-100">
                    {{ $assessments->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
