@extends('layouts.aethryna')

@section('title', 'Pathway Assessment - Question ' . $questionNumber . ' | Skills Co-op')

@section('meta_description', 'Answer a few short questions and we will match you to the Skills Co-op pilot track that fits you best. Takes about two minutes.')
@section('og_description', 'Answer a few short questions and we will match you to the Skills Co-op pilot track that fits you best. Takes about two minutes.')

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

        @if (session('status'))
            <div class="mb-6 rounded-xl border-l-4 border-teal-500 bg-white p-4 text-teal-800 shadow-sm" role="status">
                {{ session('status') }}
            </div>
        @endif

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

        {{--
            Finish later.

            Deliberately a closed disclosure rather than a form on the page: the
            job in front of someone here is answering the question, and an email
            field sitting open next to it reads as a wall. It opens only for the
            people who need it — the ones on a phone, on a bus, on a borrowed
            laptop, who would otherwise lose the lot when the session expires.
        --}}
        <div class="mb-8">
            @if ($savedEmail)
                <div class="bg-white border border-teal-100 rounded-xl p-4 text-sm text-gray-600 flex items-start gap-3">
                    <i class="fas fa-check-circle text-teal-500 mt-0.5"></i>
                    <span>
                        Your place is saved. We sent a link back into this assessment to
                        <strong class="text-teal-700">{{ $savedEmail }}</strong>, so you can stop here and pick it up whenever.
                    </span>
                </div>
            @else
                <details class="bg-white border border-gray-200 rounded-xl overflow-hidden group" {{ $errors->any() ? 'open' : '' }}>
                    <summary class="cursor-pointer list-none p-4 text-sm font-semibold text-teal-700 hover:bg-teal-50 transition-colors flex items-center gap-2">
                        <i class="fas fa-bookmark text-teal-500"></i>
                        Need to stop? Email me a link to finish later
                    </summary>
                    <div class="border-t border-gray-100 p-4">
                        <p class="text-sm text-gray-600 mb-4">
                            Your answers are saved as you go. Give us an address and we will send you a link straight back to
                            this question, so nothing is lost if you close the tab.
                        </p>

                        @if ($errors->any())
                            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('assessment.save-progress') }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="grid sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="contact_name" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">
                                        First name <span class="normal-case font-normal tracking-normal text-gray-400">(optional)</span>
                                    </label>
                                    <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name') }}"
                                           autocomplete="given-name" maxlength="120"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-teal-500 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">
                                        Email address
                                    </label>
                                    <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}"
                                           autocomplete="email" required maxlength="255"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-teal-500 focus:ring-teal-500">
                                </div>
                            </div>

                            <button type="submit"
                                    class="bg-teal-500 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                                Send me the link
                            </button>

                            {{-- Said at the point of collection, because the reminder is
                                 only foreseeable if we say so before we take the address. --}}
                            <p class="text-xs text-gray-500 leading-relaxed">
                                We use this to send you the link back in, your results when you finish, and one reminder if you
                                do not. Nothing else, and no marketing list. See our
                                <a href="{{ route('privacy') }}" class="underline hover:text-teal-600">privacy policy</a>.
                            </p>
                        </form>
                    </div>
                </details>
            @endif
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
