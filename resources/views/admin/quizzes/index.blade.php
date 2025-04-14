@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-auto">
            <h2><i class="fas fa-question-circle"></i> Manage Quizzes</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Quiz
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($quizzes->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-question-circle fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Quizzes Available</h4>
                <p class="mb-4">Start by creating your first quiz!</p>
                <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Quiz
                </a>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Questions</th>
                                <th>Time Limit</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $quiz)
                                <tr>
                                    <td>{{ $quiz->title }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $quiz->questions_count }} Questions
                                        </span>
                                    </td>
                                    <td>{{ $quiz->time_limit }} minutes</td>
                                    <td>
                                        <span class="badge bg-{{ $quiz->is_active ? 'success' : 'danger' }}">
                                            {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.quizzes.show', $quiz) }}" 
                                               class="btn btn-info" 
                                               title="View Quiz">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.questions.create') }}?quiz_id={{ $quiz->id }}" 
                                               class="btn btn-success" 
                                               title="Add Questions">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" 
                                               class="btn btn-primary" 
                                               title="Edit Quiz">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $quizzes->links() }}
                </div>
            </div>
        </div>
    @endif
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
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush 