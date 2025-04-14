<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('student.dashboard');
    }

    public function quizzes()
    {
        $quizzes = Quiz::where('is_active', true)
            ->withCount(['questions', 'attempts' => function($query) {
                $query->where('user_id', Auth::id());
            }])
            ->paginate(10);
        
        return view('student.quizzes', compact('quizzes'));
    }

    public function showQuiz(Quiz $quiz)
    {
        if (!$quiz->is_active) {
            return redirect()->route('student.quizzes')
                ->with('error', 'This quiz is not available.');
        }

        return view('student.quiz-show', compact('quiz'));
    }

    public function startQuiz(Request $request, Quiz $quiz)
    {
        if (!$quiz->is_active) {
            return redirect()->route('student.quizzes')
                ->with('error', 'This quiz is not available.');
        }

        // Check if there's already an incomplete attempt
        $existingAttempt = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->whereNull('end_time')
            ->first();

        if ($existingAttempt) {
            return redirect()->route('student.quiz.take', $existingAttempt);
        }

        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'start_time' => now(),
            'status' => 'in_progress'
        ]);

        return redirect()->route('student.quiz.take', $attempt);
    }

    public function takeQuiz(QuizAttempt $quizAttempt)
    {
        // Verify that this attempt belongs to the authenticated user
        if ($quizAttempt->user_id !== Auth::id()) {
            return redirect()->route('student.quizzes')
                ->with('error', 'Unauthorized access to quiz attempt.');
        }

        // If the attempt is already completed, redirect to results
        if ($quizAttempt->end_time) {
            return redirect()->route('student.results')
                ->with('info', 'This quiz attempt has already been completed.');
        }

        $quiz = $quizAttempt->quiz;
        
        // Get answered questions
        $answeredQuestions = $quizAttempt->answers()->pluck('question_id')->toArray();
        
        // Get the next unanswered question in sequential order
        $currentQuestion = $quiz->questions()
            ->whereNotIn('id', $answeredQuestions)
            ->orderBy('id', 'asc')  // This ensures questions are retrieved in order of creation
            ->first();
            
        if (!$currentQuestion) {
            // If no questions left, complete the quiz
            $totalQuestions = $quiz->questions->count();
            $correctAnswers = $quizAttempt->answers()->where('is_correct', true)->count();
            $score = ($correctAnswers / $totalQuestions) * 100;

            $quizAttempt->update([
                'score' => $score,
                'end_time' => now(),
                'status' => 'completed'
            ]);

            return redirect()->route('student.results')
                ->with('success', 'Quiz completed successfully!');
        }

        $currentQuestionIndex = $quizAttempt->answers()->count();
        $totalQuestions = $quiz->questions()->count();

        return view('student.quiz-take', compact('quiz', 'quizAttempt', 'currentQuestionIndex', 'currentQuestion', 'totalQuestions'));
    }

    public function submitQuiz(Request $request, QuizAttempt $quizAttempt)
    {
        // Verify that this attempt belongs to the authenticated user
        if ($quizAttempt->user_id !== Auth::id()) {
            return redirect()->route('student.quizzes')
                ->with('error', 'Unauthorized access to quiz attempt.');
        }

        // If the attempt is already completed, redirect to results
        if ($quizAttempt->end_time) {
            return redirect()->route('student.results')
                ->with('info', 'This quiz attempt has already been completed.');
        }

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'selected_option' => 'required|in:A,B,C,D'
        ]);

        $question = Question::findOrFail($request->question_id);
        
        // Check if this question has already been answered
        $existingAnswer = Answer::where('quiz_attempt_id', $quizAttempt->id)
            ->where('question_id', $question->id)
            ->first();
            
        if ($existingAnswer) {
            return redirect()->route('student.quiz.take', $quizAttempt)
                ->with('error', 'This question has already been answered.');
        }
        
        // Record the answer
        Answer::create([
            'quiz_attempt_id' => $quizAttempt->id,
            'question_id' => $question->id,
            'selected_option' => $request->selected_option,
            'is_correct' => $request->selected_option === $question->correct_option
        ]);

        // Get next question
        $answeredQuestions = $quizAttempt->answers()->pluck('question_id')->toArray();
        $nextQuestion = $quizAttempt->quiz->questions()
            ->whereNotIn('id', $answeredQuestions)
            ->orderBy('id', 'asc')  // Ensure questions are retrieved in order
            ->first();

        // If no more questions, complete the quiz
        if (!$nextQuestion) {
            $totalQuestions = $quizAttempt->quiz->questions->count();
            $correctAnswers = $quizAttempt->answers()->where('is_correct', true)->count();
            $score = ($correctAnswers / $totalQuestions) * 100;

            $quizAttempt->update([
                'score' => $score,
                'end_time' => now(),
                'status' => 'completed'
            ]);

            return redirect()->route('student.results')
                ->with('success', 'Quiz completed successfully!');
        }

        // Continue to next question by redirecting back to takeQuiz
        return redirect()->route('student.quiz.take', $quizAttempt)
            ->with('success', 'Answer submitted successfully.');
    }

    public function results()
    {
        $attempts = Auth::user()->quizAttempts()
            ->with('quiz')
            ->latest()
            ->paginate(10);
        
        return view('student.results', compact('attempts'));
    }

    public function profile()
    {
        $user = Auth::user();
        $stats = [
            'total_attempts' => $user->quizAttempts()->count(),
            'completed_quizzes' => $user->quizAttempts()->where('status', 'completed')->count(),
            'average_score' => $user->quizAttempts()->where('status', 'completed')->avg('score') ?? 0,
            'highest_score' => $user->quizAttempts()->max('score') ?? 0,
            'recent_attempts' => $user->quizAttempts()
                ->with('quiz')
                ->latest()
                ->take(5)
                ->get()
        ];

        return view('student.profile', compact('user', 'stats'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'current_password' => 'required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
