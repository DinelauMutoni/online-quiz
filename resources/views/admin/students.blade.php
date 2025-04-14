@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-users"></i> Manage Students</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($students->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Students Registered</h4>
                    <p>Students will appear here once they register.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined Date</th>
                                <th>Quiz Attempts</th>
                                <th>Average Score</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                                    <td>{{ $student->quiz_attempts_count ?? 0 }}</td>
                                    <td>
                                        @if($student->quiz_attempts_count > 0)
                                            {{ number_format($student->quiz_attempts_avg_score, 1) }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-info text-white" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#studentDetails{{ $student->id }}">
                                            <i class="fas fa-eye"></i> View Details
                                        </button>
                                    </td>
                                </tr>

                                <!-- Student Details Modal -->
                                <div class="modal fade" id="studentDetails{{ $student->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-user"></i> Student Details
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <h6>Personal Information</h6>
                                                        <p><strong>Name:</strong> {{ $student->name }}</p>
                                                        <p><strong>Email:</strong> {{ $student->email }}</p>
                                                        <p><strong>Joined:</strong> {{ $student->created_at->format('M d, Y') }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Quiz Statistics</h6>
                                                        <p><strong>Total Attempts:</strong> {{ $student->quiz_attempts_count ?? 0 }}</p>
                                                        <p><strong>Average Score:</strong> 
                                                            @if($student->quiz_attempts_count > 0)
                                                                {{ number_format($student->quiz_attempts_avg_score, 1) }}%
                                                            @else
                                                                -
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <h6>Recent Quiz Attempts</h6>
                                                @if($student->quizAttempts->isEmpty())
                                                    <p class="text-muted">No quiz attempts yet.</p>
                                                @else
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Quiz</th>
                                                                    <th>Score</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($student->quizAttempts->take(5) as $attempt)
                                                                    <tr>
                                                                        <td>{{ $attempt->quiz->title }}</td>
                                                                        <td>{{ number_format($attempt->score, 1) }}%</td>
                                                                        <td>{{ $attempt->created_at->format('M d, Y') }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
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
                    {{ $students->links() }}
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
</style>
@endpush 