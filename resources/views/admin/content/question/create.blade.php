@extends('layouts.aethryna')

@section('title', 'Create Question | Skills Co-op')

@section('content')
    <!-- Header -->
    <section class="bg-gradient-to-r from-teal-700 to-teal-900 text-white py-12 px-8">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">Add Assessment Question</h1>
                <p class="text-lg opacity-90">Create a new question for the assessment flow</p>
            </div>
            <a href="{{ route('admin.content') }}" class="text-white hover:text-yellow-400 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Content
            </a>
        </div>
    </section>

    <!-- Form Section -->
    <section class="bg-gray-50 py-12 px-8">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-md p-8">
            <form action="{{ route('admin.content.question.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Question Text -->
                <div>
                    <label for="text" class="block text-sm font-semibold text-gray-700 mb-1">Question Text</label>
                    <textarea name="text" id="text" rows="3" class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200" required>{{ old('text') }}</textarea>
                </div>

                <!-- Sequence Number -->
                <div>
                    <label for="question_number" class="block text-sm font-semibold text-gray-700 mb-1">Question Number (Sequence)</label>
                    <input type="number" name="question_number" id="question_number" value="{{ old('question_number') }}" class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                    <p class="text-xs text-gray-500 mt-1">Determines the order in which questions appear.</p>
                </div>

                <!-- Answers -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-teal-800 mb-4">Answers & Scoring</h3>
                    <p class="text-sm text-gray-600 mb-4">Add possible answers and assign cluster points to them. Clusters: T (Technical), C (Creative), B (Business), S (Security), F (Foundation).</p>
                    
                    <div id="answers-container" class="space-y-6">
                        <!-- Initial Answer Block (A) -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 relative">
                            <span class="absolute top-2 right-2 text-xs font-bold text-gray-400">Option A</span>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Answer Text</label>
                                    <input type="text" name="answers[0][text]" class="w-full rounded-lg border-gray-300 text-sm" placeholder="e.g. I like to take things apart" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cluster Points</label>
                                    <select name="answers[0][clusters][]" multiple class="w-full rounded-lg border-gray-300 text-sm h-10" required>
                                        <option value="T">Technical (T)</option>
                                        <option value="C">Creative (C)</option>
                                        <option value="B">Business (B)</option>
                                        <option value="S">Security (S)</option>
                                        <option value="F">Foundation (F)</option>
                                    </select>
                                    <p class="text-xs text-gray-400 mt-1">Hold Ctrl/Cmd to select multiple.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Initial Answer Block (B) -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 relative">
                            <span class="absolute top-2 right-2 text-xs font-bold text-gray-400">Option B</span>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Answer Text</label>
                                    <input type="text" name="answers[1][text]" class="w-full rounded-lg border-gray-300 text-sm" placeholder="e.g. I like to draw and design" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cluster Points</label>
                                    <select name="answers[1][clusters][]" multiple class="w-full rounded-lg border-gray-300 text-sm h-10" required>
                                        <option value="T">Technical (T)</option>
                                        <option value="C">Creative (C)</option>
                                        <option value="B">Business (B)</option>
                                        <option value="S">Security (S)</option>
                                        <option value="F">Foundation (F)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <button type="button" onclick="addAnswer()" class="mt-4 text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition-colors">
                        + Add Another Option
                    </button>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-white px-6 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-1">
                        Save Question
                    </button>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
    <script>
        let answerCount = 2; // Initial count

        function addAnswer() {
            const container = document.getElementById('answers-container');
            const index = answerCount;
            const letter = String.fromCharCode(65 + index); // A, B, C...

            const div = document.createElement('div');
            div.className = 'bg-gray-50 p-4 rounded-xl border border-gray-200 relative mt-4';
            div.innerHTML = `
                <span class="absolute top-2 right-2 text-xs font-bold text-gray-400">Option ${letter}</span>
                <button type="button" class="absolute top-2 right-16 text-xs text-red-500 hover:text-red-700 font-bold" onclick="this.parentElement.remove()">Remove</button>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Answer Text</label>
                        <input type="text" name="answers[${index}][text]" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Answer option..." required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Cluster Points</label>
                        <select name="answers[${index}][clusters][]" multiple class="w-full rounded-lg border-gray-300 text-sm h-10" required>
                            <option value="T">Technical (T)</option>
                            <option value="C">Creative (C)</option>
                            <option value="B">Business (B)</option>
                            <option value="S">Security (S)</option>
                            <option value="F">Foundation (F)</option>
                        </select>
                    </div>
                </div>
            `;
            container.appendChild(div);
            answerCount++;
        }
    </script>
    @endpush
@endsection
