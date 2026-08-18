<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{

    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Display the users management page.
     */
    public function users(Request $request)
    {
        // Paginated at 10 with no search, so finding one person meant paging
        // through everyone. Matches on name or email.
        $q = trim((string) $request->query('q'));

        $users = \App\Models\User::query()
            ->when($q !== '', fn ($query) => $query->where(fn ($w) =>
                $w->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")
            ))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    /**
     * Display the reports page.
     */
    public function reports()
    {
        $assessments = \App\Models\Assessment::with('user')->latest()->paginate(10);
        return view('admin.reports.index', compact('assessments'));
    }

    /**
     * Display the content management page.
     */
    public function content()
    {
        $pathways = \App\Models\Pathway::withCount('assessmentResults')->get();
        $questions = \App\Models\Question::with('answers')->paginate(10);
        return view('admin.content.index', compact('pathways', 'questions'));
    }

    // --- Pathway Methods ---

    public function createPathway()
    {
        return view('admin.content.pathway.create');
    }

    public function storePathway(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:technical,creative,business,security,foundation',
            'description' => 'required|string',
            'recommended_for' => 'required|string',
            'skills' => 'required|array',
            'career_paths' => 'required|array',
            'difficulty_level' => 'required|string|in:Beginner,Intermediate,Advanced',
            'duration_months' => 'required|integer',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = true;

        \App\Models\Pathway::create($validated);

        return redirect()->route('admin.content')->with('success', 'Pathway created successfully.');
    }

    public function editPathway(\App\Models\Pathway $pathway)
    {
        return view('admin.content.pathway.edit', compact('pathway'));
    }

    public function updatePathway(Request $request, \App\Models\Pathway $pathway)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:technical,creative,business,security,foundation',
            'description' => 'required|string',
            'recommended_for' => 'required|string',
            'skills' => 'required|array',
            'career_paths' => 'required|array',
            'difficulty_level' => 'required|string|in:Beginner,Intermediate,Advanced',
            'duration_months' => 'required|integer',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        $pathway->update($validated);

        return redirect()->route('admin.content')->with('success', 'Pathway updated successfully.');
    }

    public function destroyPathway(\App\Models\Pathway $pathway)
    {
        $pathway->delete();
        return redirect()->route('admin.content')->with('success', 'Pathway deleted successfully.');
    }

    // --- Question Methods ---

    public function createQuestion()
    {
        return view('admin.content.question.create');
    }

    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'question_number' => 'required|integer|unique:questions,question_number',
            'answers' => 'required|array|min:2',
            'answers.*.text' => 'required|string',
            'answers.*.clusters' => 'required|array',
        ]);

        $question = \App\Models\Question::create([
            'text' => $validated['text'],
            'question_number' => $validated['question_number'],
            'is_active' => true,
        ]);

        foreach ($validated['answers'] as $index => $answerData) {
            $question->answers()->create([
                'option_label' => chr(65 + $index), // A, B, C...
                'text' => $answerData['text'],
                'clusters' => $answerData['clusters'],
            ]);
        }

        return redirect()->route('admin.content')->with('success', 'Question created successfully.');
    }

    public function editQuestion(\App\Models\Question $question)
    {
        $question->load('answers');
        return view('admin.content.question.edit', compact('question'));
    }

    public function updateQuestion(Request $request, \App\Models\Question $question)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'question_number' => 'required|integer|unique:questions,question_number,' . $question->id,
            'answers' => 'required|array|min:2',
            'answers.*.id' => 'sometimes|exists:answers,id',
            'answers.*.text' => 'required|string',
            'answers.*.clusters' => 'required|array',
        ]);

        $question->update([
            'text' => $validated['text'],
            'question_number' => $validated['question_number'],
        ]);

        // Sync answers simply by deleting old and recreating new ones to handle dynamic count changes easily
        // Or updated existing ones. For simplicity in this iteration, let's update if ID exists, create if not.
        // Actually simplest robust way for this specific structure: delete all answers and recreate them to ensure indices A, B... align.
        $question->answers()->delete();

        foreach ($validated['answers'] as $index => $answerData) {
             $question->answers()->create([
                'option_label' => chr(65 + $index),
                'text' => $answerData['text'],
                'clusters' => $answerData['clusters'],
            ]);
        }

        return redirect()->route('admin.content')->with('success', 'Question updated successfully.');
    }

    public function destroyQuestion(\App\Models\Question $question)
    {
        $question->answers()->delete(); // Cascade typically handles this but good to be explicit or ensure DB FK
        $question->delete();
        return redirect()->route('admin.content')->with('success', 'Question deleted successfully.');
    }

    // --- User Methods ---

    public function destroyUser(\App\Models\User $user)
    {
        // Prevent deleting self
        if (\Illuminate\Support\Facades\Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}
