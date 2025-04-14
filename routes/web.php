<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role == 'admin' ? 
            redirect()->route('admin.dashboard') : 
            redirect()->route('student.dashboard');
    }
    return view('welcome');
});

Auth::routes();

// Redirect /home to role-specific dashboard
Route::get('/home', function () {
    return auth()->user()->is_admin ? 
        redirect()->route('admin.dashboard') : 
        redirect()->route('student.dashboard');
})->middleware('auth')->name('home');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('quizzes', QuizController::class);
    Route::resource('questions', QuestionController::class);
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/results', [AdminController::class, 'results'])->name('results');
    Route::get('/students', [AdminController::class, 'students'])->name('students');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/quizzes', [StudentController::class, 'quizzes'])->name('quizzes');
    
    // Quiz routes
    Route::get('/quiz/{quiz}', [StudentController::class, 'showQuiz'])->name('quiz.show');
    Route::post('/quiz/{quiz}/start', [StudentController::class, 'startQuiz'])->name('quiz.start');
    Route::get('/quiz/{quizAttempt}/take', [StudentController::class, 'takeQuiz'])->name('quiz.take');
    Route::post('/quiz/{quizAttempt}/submit', [StudentController::class, 'submitQuiz'])->name('quiz.submit');
    
    Route::get('/results', [StudentController::class, 'results'])->name('results');
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::put('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
});
