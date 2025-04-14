@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-user-graduate"></i> Student Menu
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('student.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('student.quizzes') }}" class="list-group-item list-group-item-action {{ request()->routeIs('student.quizzes') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i> Available Quizzes
                    </a>
                    <a href="{{ route('student.results') }}" class="list-group-item list-group-item-action {{ request()->routeIs('student.results') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> My Results
                    </a>
                    <a href="{{ route('student.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-question-circle"></i> Available Quizzes
                    </div>
                </div>
                <div class="card-body">
                    @if($quizzes->count() > 0)
                        <div class="row">
                            @foreach($quizzes as $quiz)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $quiz->title }}</h5>
                                            <p class="card-text">{{ Str::limit($quiz->description, 100) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="quiz-info">
                                                    <small class="text-muted">
                                                        <i class="fas fa-list-ul"></i> {{ $quiz->questions_count }} Questions
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i> {{ $quiz->time_limit }} Minutes
                                                    </small>
                                                </div>
                                                <div class="quiz-status">
                                                    @if($quiz->attempts_count > 0)
                                                        <span class="badge bg-success">Completed</span>
                                                    @else
                                                        <a href="{{ route('student.quiz.show', $quiz) }}" class="btn btn-primary">
                                                            Start Quiz
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $quizzes->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h4>No Quizzes Available</h4>
                            <p class="text-muted">There are currently no quizzes available for you to take.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1rem;
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .list-group-item {
        border: none;
        padding: 0.75rem 1.25rem;
    }
    .list-group-item.active {
        background-color: #007bff;
        border-color: #007bff;
    }
    .list-group-item i {
        margin-right: 0.5rem;
    }
    .quiz-info small {
        display: inline-block;
        margin-right: 1rem;
    }
    .quiz-info i {
        margin-right: 0.25rem;
    }
    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush 