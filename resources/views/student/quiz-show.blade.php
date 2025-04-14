@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-question-circle"></i> {{ $quiz->title }}
                </div>
                <div class="card-body">
                    <div class="quiz-info mb-4">
                        <h4>Quiz Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p><i class="fas fa-list-ul text-primary"></i> <strong>Total Questions:</strong> {{ $quiz->questions->count() }}</p>
                                <p><i class="fas fa-clock text-primary"></i> <strong>Time Limit:</strong> {{ $quiz->time_limit }} Minutes</p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="fas fa-calendar-alt text-primary"></i> <strong>Available Until:</strong> {{ $quiz->end_date ? $quiz->end_date->format('M d, Y') : 'No end date' }}</p>
                                <p><i class="fas fa-trophy text-primary"></i> <strong>Passing Score:</strong> 70%</p>
                            </div>
                        </div>
                    </div>

                    <div class="quiz-description mb-4">
                        <h4>Description</h4>
                        <p>{{ $quiz->description }}</p>
                    </div>

                    <div class="quiz-rules mb-4">
                        <h4>Rules & Instructions</h4>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> You must complete the quiz within the time limit</li>
                            <li><i class="fas fa-check text-success"></i> Each question must be answered in sequence</li>
                            <li><i class="fas fa-check text-success"></i> You cannot go back to previous questions</li>
                            <li><i class="fas fa-check text-success"></i> Results will be shown immediately after completion</li>
                            <li><i class="fas fa-check text-success"></i> A score of 70% or higher is required to pass</li>
                        </ul>
                    </div>

                    <div class="text-center">
                        <form action="{{ route('student.quiz.start', $quiz) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-play-circle"></i> Start Quiz
                            </button>
                        </form>
                        <a href="{{ route('student.quizzes') }}" class="btn btn-link mt-2">
                            <i class="fas fa-arrow-left"></i> Back to Quizzes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .quiz-info i, .quiz-rules i {
        margin-right: 0.5rem;
    }
    .quiz-rules li {
        margin-bottom: 0.5rem;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
@endpush 