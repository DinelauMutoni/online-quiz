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
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-tachometer-alt"></i> Student Dashboard
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Quizzes Taken</h5>
                                    <h2 class="mb-0">{{ Auth::user()->quizAttempts->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Average Score</h5>
                                    <h2 class="mb-0">
                                        {{ Auth::user()->quizAttempts->avg('score') ? number_format(Auth::user()->quizAttempts->avg('score'), 1) : '0.0' }}%
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Available Quizzes</h5>
                                    <h2 class="mb-0">{{ App\Models\Quiz::where('is_active', true)->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Recent Quiz Results</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Quiz</th>
                                            <th>Score</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(Auth::user()->quizAttempts()->with('quiz')->latest()->take(5)->get() as $attempt)
                                        <tr>
                                            <td>{{ $attempt->quiz->title }}</td>
                                            <td>{{ $attempt->score }}%</td>
                                            <td>{{ $attempt->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($attempt->score >= 70)
                                                    <span class="badge bg-success">Passed</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1rem;
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
    .bg-primary {
        background-color: #007bff !important;
    }
    .card-header {
        font-weight: 600;
    }
</style>
@endpush 