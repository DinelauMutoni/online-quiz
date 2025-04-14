@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-chart-bar"></i> My Quiz Results
                    </div>
                    <div>
                        <a href="{{ route('student.quizzes') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-list"></i> Available Quizzes
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($attempts->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">You haven't taken any quizzes yet.</h5>
                            <a href="{{ route('student.quizzes') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-play"></i> Take a Quiz
                            </a>
                        </div>
                    @else
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" id="sortOrder">
                                        <option value="latest">Latest First</option>
                                        <option value="oldest">Oldest First</option>
                                        <option value="score">Highest Score</option>
                                        <option value="title">Quiz Title</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Quiz</th>
                                        <th>Started</th>
                                        <th>Completed</th>
                                        <th>Time Taken</th>
                                        <th>Score / Required</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attempts as $attempt)
                                        <tr>
                                            <td>{{ $attempt->quiz->title }}</td>
                                            <td>{{ $attempt->start_time ? $attempt->start_time->format('M d, Y H:i') : '-' }}</td>
                                            <td>
                                                @if($attempt->end_time)
                                                    {{ $attempt->end_time->format('M d, Y H:i') }}
                                                @else
                                                    <span class="badge badge-warning">In Progress</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attempt->end_time && $attempt->start_time)
                                                    {{ $attempt->end_time->diffForHumans($attempt->start_time, true) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($attempt->end_time)
                                                    <div class="score-container">
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar {{ $attempt->score >= $attempt->quiz->passing_score ? 'bg-success' : 'bg-danger' }}"
                                                                role="progressbar"
                                                                style="width: {{ $attempt->score }}%"
                                                                aria-valuenow="{{ $attempt->score }}"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100">
                                                                {{ number_format($attempt->score, 1) }}%
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">Required: {{ $attempt->quiz->passing_score }}%</small>
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($attempt->end_time)
                                                    @if($attempt->score >= $attempt->quiz->passing_score)
                                                        <span class="badge badge-success">Passed</span>
                                                    @else
                                                        <span class="badge badge-danger">Failed</span>
                                                    @endif
                                                @else
                                                    @if($attempt->start_time)
                                                        @if($attempt->start_time->addMinutes($attempt->quiz->time_limit)->isPast())
                                                            <span class="badge badge-danger">Expired</span>
                                                        @else
                                                            <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-secondary">Not Started</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($attempt->end_time)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-info view-details" 
                                                            data-toggle="modal" 
                                                            data-target="#attemptDetails{{ $attempt->id }}">
                                                        <i class="fas fa-eye"></i> Details
                                                    </button>

                                                    <!-- Attempt Details Modal -->
                                                    <div class="modal fade" id="attemptDetails{{ $attempt->id }}" tabindex="-1" role="dialog" aria-labelledby="attemptDetailsLabel{{ $attempt->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="attemptDetailsLabel{{ $attempt->id }}">
                                                                        Quiz Results: {{ $attempt->quiz->title }}
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="result-summary mb-4">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <div class="card bg-light">
                                                                                    <div class="card-body text-center">
                                                                                        <h6 class="card-subtitle mb-2 text-muted">Final Score</h6>
                                                                                        <h4 class="card-title mb-0 {{ $attempt->score >= $attempt->quiz->passing_score ? 'text-success' : 'text-danger' }}">
                                                                                            {{ number_format($attempt->score, 1) }}%
                                                                                        </h4>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="card bg-light">
                                                                                    <div class="card-body text-center">
                                                                                        <h6 class="card-subtitle mb-2 text-muted">Time Taken</h6>
                                                                                        <h4 class="card-title mb-0">
                                                                                            {{ $attempt->end_time->diffForHumans($attempt->start_time, true) }}
                                                                                        </h4>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="card bg-light">
                                                                                    <div class="card-body text-center">
                                                                                        <h6 class="card-subtitle mb-2 text-muted">Status</h6>
                                                                                        <h4 class="card-title mb-0">
                                                                                            <span class="badge {{ $attempt->score >= $attempt->quiz->passing_score ? 'badge-success' : 'badge-danger' }}">
                                                                                                {{ $attempt->score >= $attempt->quiz->passing_score ? 'Passed' : 'Failed' }}
                                                                                            </span>
                                                                                        </h4>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <h6 class="border-bottom pb-2">Question Review</h6>
                                                                    <div class="question-review">
                                                                        @foreach($attempt->answers as $answer)
                                                                            <div class="question-item mb-3 p-3 {{ $answer->is_correct ? 'border-left border-success' : 'border-left border-danger' }}">
                                                                                <div class="question-text mb-2">
                                                                                    <strong>Q{{ $loop->iteration }}:</strong> 
                                                                                    {{ $answer->question->question_text }}
                                                                                </div>
                                                                                <div class="answer-details">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <small class="text-muted">Your Answer:</small><br>
                                                                                            <span class="{{ $answer->is_correct ? 'text-success' : 'text-danger' }}">
                                                                                                {{ $answer->selected_option }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <small class="text-muted">Correct Answer:</small><br>
                                                                                            <span class="text-success">
                                                                                                {{ $answer->question->correct_option }}
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if($attempt->start_time && !$attempt->start_time->addMinutes($attempt->quiz->time_limit)->isPast())
                                                        <a href="{{ route('student.quiz.take', $attempt) }}" class="btn btn-sm btn-primary">
                                                            Continue
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
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
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.9em;
        padding: 0.5em 0.75em;
    }
    .table td {
        vertical-align: middle;
    }
    .score-container {
        min-width: 150px;
    }
    .score-container .progress {
        margin-bottom: 0.25rem;
    }
    .question-item {
        background-color: #f8f9fa;
        border-radius: 4px;
    }
    .question-item.border-left {
        border-left-width: 4px !important;
    }
    .modal-lg {
        max-width: 800px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize all modals
    $('.modal').modal({
        show: false
    });

    // Handle Details button click
    $('.view-details').click(function() {
        var targetModal = $(this).data('target');
        $(targetModal).modal('show');
    });
});
</script>
@endpush 