@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-chart-bar"></i> Quiz Results</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($attempts->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Quiz Attempts Yet</h4>
                    <p>Results will appear here once students take quizzes.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Quiz</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Time Taken</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $attempt)
                                <tr>
                                    <td>{{ $attempt->user->name }}</td>
                                    <td>{{ $attempt->quiz->title }}</td>
                                    <td>{{ number_format($attempt->score, 1) }}%</td>
                                    <td>
                                        @if($attempt->score >= $attempt->quiz->passing_score)
                                            <span class="badge bg-success">Passed</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attempt->completed_at)
                                            {{ $attempt->completed_at->diffForHumans($attempt->started_at, true) }}
                                        @else
                                            In Progress
                                        @endif
                                    </td>
                                    <td>{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-info text-white"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#attemptDetails{{ $attempt->id }}">
                                            <i class="fas fa-eye"></i> View Details
                                        </button>
                                    </td>
                                </tr>

                                <!-- Attempt Details Modal -->
                                <div class="modal fade" id="attemptDetails{{ $attempt->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-clipboard-list"></i> Attempt Details
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <h6>Student Information</h6>
                                                        <p><strong>Name:</strong> {{ $attempt->user->name }}</p>
                                                        <p><strong>Email:</strong> {{ $attempt->user->email }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Quiz Information</h6>
                                                        <p><strong>Quiz:</strong> {{ $attempt->quiz->title }}</p>
                                                        <p><strong>Time Limit:</strong> {{ $attempt->quiz->time_limit }} minutes</p>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <h6>Attempt Details</h6>
                                                        <p><strong>Started:</strong> {{ $attempt->started_at->format('M d, Y H:i') }}</p>
                                                        <p><strong>Completed:</strong> 
                                                            @if($attempt->completed_at)
                                                                {{ $attempt->completed_at->format('M d, Y H:i') }}
                                                            @else
                                                                In Progress
                                                            @endif
                                                        </p>
                                                        <p><strong>Time Taken:</strong> 
                                                            @if($attempt->completed_at)
                                                                {{ $attempt->completed_at->diffForHumans($attempt->started_at, true) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Results</h6>
                                                        <p><strong>Score:</strong> {{ number_format($attempt->score, 1) }}%</p>
                                                        <p><strong>Status:</strong> 
                                                            @if($attempt->score >= $attempt->quiz->passing_score)
                                                                <span class="badge bg-success">Passed</span>
                                                            @else
                                                                <span class="badge bg-danger">Failed</span>
                                                            @endif
                                                        </p>
                                                        <p><strong>Passing Score:</strong> {{ $attempt->quiz->passing_score }}%</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $attempts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .modal-body h6 {
        color: #007bff;
        margin-bottom: 1rem;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
    }
</style>
@endpush 