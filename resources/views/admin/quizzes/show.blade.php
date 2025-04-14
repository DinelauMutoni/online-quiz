@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-question-circle"></i> Quiz Details
                    </div>
                    <div>
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> Edit Quiz
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4>{{ $quiz->title }}</h4>
                            <p class="text-muted">{{ $quiz->description }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-{{ $quiz->is_active ? 'success' : 'danger' }} mb-2">
                                {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary">Quiz Settings</h6>
                            <ul class="list-unstyled">
                                <li><strong>Time Limit:</strong> {{ $quiz->time_limit }} minutes</li>
                                <li><strong>Passing Score:</strong> {{ $quiz->passing_score }}%</li>
                                <li><strong>Total Questions:</strong> {{ $quiz->questions->count() }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Statistics</h6>
                            <ul class="list-unstyled">
                                <li><strong>Total Attempts:</strong> {{ $quiz->attempts->count() }}</li>
                                <li><strong>Average Score:</strong> 
                                    @if($quiz->attempts->count() > 0)
                                        {{ number_format($quiz->attempts->avg('score'), 1) }}%
                                    @else
                                        No attempts yet
                                    @endif
                                </li>
                                <li><strong>Pass Rate:</strong> 
                                    @if($quiz->attempts->count() > 0)
                                        {{ number_format(($quiz->attempts->where('score', '>=', $quiz->passing_score)->count() / $quiz->attempts->count()) * 100, 1) }}%
                                    @else
                                        No attempts yet
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if($quiz->instructions)
                        <div class="mb-4">
                            <h6 class="text-primary">Instructions</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    {{ $quiz->instructions }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="text-primary">Questions</h6>
                        @if($quiz->questions->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> No questions added yet.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Question</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quiz->questions as $index => $question)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ Str::limit($question->question_text, 50) }}</td>
                                                <td>{{ ucfirst($question->type) }}</td>
                                                <td>
                                                    <a href="{{ route('admin.questions.edit', $question) }}" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary">Recent Attempts</h6>
                        @if($quiz->attempts->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No attempts yet.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Score</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quiz->attempts->take(5) as $attempt)
                                            <tr>
                                                <td>{{ $attempt->user->name }}</td>
                                                <td>{{ number_format($attempt->score, 1) }}%</td>
                                                <td>
                                                    <span class="badge bg-{{ $attempt->score >= $quiz->passing_score ? 'success' : 'danger' }}">
                                                        {{ $attempt->score >= $quiz->passing_score ? 'Passed' : 'Failed' }}
                                                    </span>
                                                </td>
                                                <td>{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Quizzes
                        </a>
                        <div>
                            <a href="{{ route('admin.questions.create') }}?quiz_id={{ $quiz->id }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add Question
                            </a>
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Quiz
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
    }
    .table td {
        vertical-align: middle;
    }
    h6.text-primary {
        margin-bottom: 1rem;
        font-weight: 600;
    }
</style>
@endpush 