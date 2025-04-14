<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quiz::withCount(['questions', 'attempts'])
            ->latest()
            ->paginate(10);

        return view('admin.quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.quizzes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'time_limit' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'instructions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Quiz::create($validated);

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Quiz created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['questions', 'attempts.user']);
        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        $quiz->load('questions');
        return view('admin.quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'time_limit' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'instructions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $quiz->update($validated);

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Quiz updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quiz $quiz)
    {
        // Check if there are any attempts
        if ($quiz->attempts()->exists()) {
            return redirect()
                ->route('admin.quizzes.index')
                ->with('error', 'Cannot delete quiz with existing attempts.');
        }

        $quiz->questions()->delete(); // Delete associated questions
        $quiz->delete();

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }
}
