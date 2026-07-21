@extends('layouts.aethryna')

@section('title', 'Pathway Assessment - Question ' . $questionNumber . ' | SkillsCo-op')

@section('meta_description', 'Answer a few short questions and we will match you to the SkillsCo-op pilot track that fits you best. Takes about two minutes.')
@section('og_description', 'Answer a few short questions and we will match you to the SkillsCo-op pilot track that fits you best. Takes about two minutes.')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-teal-50 to-teal-100 py-16 px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-teal-700">Pathway Assessment</h1>
                <span class="text-sm text-gray-600">{{ $questionNumber }} of {{ $totalQuestions }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-teal-500 h-2 rounded-full transition-all duration-500 ease-out" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-teal-500 text-white text-sm font-bold px-3 py-1 rounded-full uppercase">
                        Question {{ $questionNumber }}
                    </span>
                    <span class="bg-gray-100 text-gray-600 text-sm font-medium px-3 py-1 rounded-full">
                        Section {{ $question->section }}
                    </span>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 leading-relaxed">
                    {{ $question->question_text }}
                </h2>
            </div>

            <!-- Answer Options -->
            <form action="{{ route('assessment.answer', $questionNumber) }}" method="POST" id="answerForm">
                @csrf
                <div class="space-y-3">
                    @foreach($question->answers as $answer)
                    <label class="answer-option flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-teal-300 hover:bg-teal-50 transition-all duration-200">
                        <input type="radio" name="answer" value="{{ $answer->option_label }}" class="mr-4 text-teal-500 focus:ring-teal-500" required>
                        <div class="flex-1">
                            <span class="text-gray-800 font-medium">{{ $answer->answer_text }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Choose the option that best describes you
                    </div>
                    <button type="submit" class="bg-teal-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" id="submitBtn" disabled>
                        {{ $questionNumber === $totalQuestions ? 'Complete Assessment' : 'Next Question' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-blue-800">Assessment Tips</h4>
                    <div class="mt-1 text-sm text-blue-700">
                        <p>• Answer based on your natural preferences, not what you think you should choose</p>
                        <p>• There are no right or wrong answers - this is about finding your best fit</p>
                        <p>• Take your time to reflect on each question</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .answer-option input[type="radio"] {
        width: 20px;
        height: 20px;
    }

    .answer-option input[type="radio"]:checked + div {
        color: #0f766e;
    }

    .answer-option:has(input[type="radio"]:checked) {
        border-color: #14b8a6;
        background-color: #f0fdfa;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    const submitBtn = document.getElementById('submitBtn');

    // Enable submit button when an option is selected
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    });

    // Add click handler to option labels
    const answerOptions = document.querySelectorAll('.answer-option');
    answerOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            radio.dispatchEvent(new Event('change'));
        });
    });
});
</script>
@endpush
@endsection
