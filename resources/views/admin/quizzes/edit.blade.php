@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-edit"></i> Edit Quiz: {{ $quiz->title }}
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Quiz Title</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $quiz->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      required>{{ old('description', $quiz->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="time_limit" class="form-label">Time Limit (minutes)</label>
                                <input type="number" 
                                       class="form-control @error('time_limit') is-invalid @enderror" 
                                       id="time_limit" 
                                       name="time_limit" 
                                       value="{{ old('time_limit', $quiz->time_limit) }}" 
                                       min="1" 
                                       required>
                                @error('time_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="passing_score" class="form-label">Passing Score (%)</label>
                                <input type="number" 
                                       class="form-control @error('passing_score') is-invalid @enderror" 
                                       id="passing_score" 
                                       name="passing_score" 
                                       value="{{ old('passing_score', $quiz->passing_score) }}" 
                                       min="0" 
                                       max="100" 
                                       required>
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $quiz->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                            <div class="form-text">Inactive quizzes won't be visible to students.</div>
                        </div>

                        <div class="mb-3">
                            <label for="instructions" class="form-label">Quiz Instructions</label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                      id="instructions" 
                                      name="instructions" 
                                      rows="4">{{ old('instructions', $quiz->instructions) }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Provide any special instructions for students taking this quiz.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Quiz
                            </a>
                            <div>
                                <a href="{{ route('admin.questions.create') }}?quiz_id={{ $quiz->id }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add Question
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Quiz
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if($quiz->questions->count() > 0)
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-list"></i> Questions ({{ $quiz->questions->count() }})
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Type</th>
                                    <th>Points</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quiz->questions as $index => $question)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ Str::limit($question->question_text, 50) }}</td>
                                        <td>{{ str_replace('_', ' ', ucfirst($question->type)) }}</td>
                                        <td>{{ $question->points }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.questions.edit', $question) }}" 
                                                   class="btn btn-primary" 
                                                   title="Edit Question">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.questions.destroy', $question) }}" 
                                                      method="POST" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this question?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete Question">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-switch {
        padding-left: 2.5em;
    }
    .form-check-input {
        cursor: pointer;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush 