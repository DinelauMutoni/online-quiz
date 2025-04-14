@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Profile Information</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5>Change Password</h5>
                        <p class="text-muted small">Leave password fields empty if you don't want to change it.</p>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                id="password_confirmation" name="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quiz Statistics -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Quiz Statistics</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Attempts:</span>
                        <strong>{{ $stats['total_attempts'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Completed Quizzes:</span>
                        <strong>{{ $stats['completed_quizzes'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Average Score:</span>
                        <strong>{{ number_format($stats['average_score'], 1) }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Highest Score:</span>
                        <strong>{{ number_format($stats['highest_score'], 1) }}%</strong>
                    </div>
                </div>
            </div>

            <!-- Recent Attempts -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Recent Attempts</h4>
                </div>
                <div class="card-body">
                    @if($stats['recent_attempts']->isEmpty())
                        <p class="text-muted">No quiz attempts yet.</p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($stats['recent_attempts'] as $attempt)
                                <div class="list-group-item px-0">
                                    <h6 class="mb-1">{{ $attempt->quiz->title }}</h6>
                                    <p class="mb-1 text-muted small">
                                        Score: {{ number_format($attempt->score, 1) }}%<br>
                                        {{ $attempt->completed_at ? $attempt->completed_at->diffForHumans() : 'In Progress' }}
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