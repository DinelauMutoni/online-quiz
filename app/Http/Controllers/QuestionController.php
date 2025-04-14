<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->has('quiz_id')) {
            return redirect()->route('admin.quizzes.index')
                ->with('error', 'Quiz ID is required to add questions.');
        }

        $quiz = Quiz::findOrFail($request->quiz_id);
        return view('admin.questions.create', compact('quiz'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required_if:type,multiple_choice|nullable|string',
            'option_d' => 'required_if:type,multiple_choice|nullable|string',
            'correct_option' => 'required|in:A,B,C,D',
            'points' => 'required|integer|min:1'
        ]);

        $question = Question::create([
            'quiz_id' => $request->quiz_id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->type === 'multiple_choice' ? $request->option_c : null,
            'option_d' => $request->type === 'multiple_choice' ? $request->option_d : null,
            'correct_option' => $request->correct_option,
            'points' => $request->points
        ]);

        if ($request->has('add_another')) {
            return redirect()->route('admin.questions.create', ['quiz_id' => $request->quiz_id])
                ->with('success', 'Question added successfully. Add another one.');
        }

        return redirect()->route('admin.quizzes.show', $request->quiz_id)
            ->with('success', 'Question added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required_if:type,multiple_choice|nullable|string',
            'option_d' => 'required_if:type,multiple_choice|nullable|string',
            'correct_option' => 'required|in:A,B,C,D',
            'points' => 'required|integer|min:1'
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->type === 'multiple_choice' ? $request->option_c : null,
            'option_d' => $request->type === 'multiple_choice' ? $request->option_d : null,
            'correct_option' => $request->correct_option,
            'points' => $request->points
        ]);

        return redirect()->route('admin.quizzes.show', $question->quiz_id)
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $quizId = $question->quiz_id;
        $question->delete();

        return redirect()->route('admin.quizzes.show', $quizId)
            ->with('success', 'Question deleted successfully.');
    }
}
