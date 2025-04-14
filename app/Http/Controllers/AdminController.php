<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $recentQuizzes = Quiz::withCount('questions')
            ->latest()
            ->take(5)
            ->get();

        $recentAttempts = QuizAttempt::with(['user', 'quiz'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_quizzes' => Quiz::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_attempts' => QuizAttempt::count(),
            'average_score' => QuizAttempt::avg('score')
        ];

        return view('admin.dashboard', compact('recentQuizzes', 'recentAttempts', 'stats'));
    }

    public function students()
    {
        $students = User::where('role', 'student')
            ->withCount('quizAttempts')
            ->paginate(10);

        return view('admin.students', compact('students'));
    }

    public function results()
    {
        $attempts = QuizAttempt::with(['user', 'quiz'])
            ->latest()
            ->paginate(15);

        return view('admin.results', compact('attempts'));
    }
} 