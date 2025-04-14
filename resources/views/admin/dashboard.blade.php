@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Quizzes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_quizzes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_students'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Quiz Attempts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_attempts'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Average Score</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['average_score'], 1) }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary btn-block w-100">
                                <i class="fas fa-plus-circle"></i> Create New Quiz
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.questions.create') }}" class="btn btn-success btn-block w-100">
                                <i class="fas fa-question-circle"></i> Add Questions
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.students') }}" class="btn btn-info btn-block w-100">
                                <i class="fas fa-users"></i> View Students
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.results') }}" class="btn btn-warning btn-block w-100">
                                <i class="fas fa-chart-bar"></i> View Results
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Quizzes</h5>
                </div>
                <div class="card-body">
                    @if($recentQuizzes->isEmpty())
                        <p class="text-muted">No quizzes created yet.</p>
                    @else
                        <div class="list-group">
                            @foreach($recentQuizzes as $quiz)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $quiz->title }}</h6>
                                        <small>{{ $quiz->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">Questions: {{ $quiz->questions_count }}</p>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.questions.create', ['quiz_id' => $quiz->id]) }}" class="btn btn-success">
                                            <i class="fas fa-plus"></i> Add Questions
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Quiz Attempts</h5>
                </div>
                <div class="card-body">
                    @if($recentAttempts->isEmpty())
                        <p class="text-muted">No quiz attempts yet.</p>
                    @else
                        <div class="list-group">
                            @foreach($recentAttempts as $attempt)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $attempt->user->name }}</h6>
                                        <small>{{ $attempt->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        Quiz: {{ $attempt->quiz->title }}<br>
                                        Score: {{ number_format($attempt->score, 1) }}%
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush 