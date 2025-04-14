@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-plus-circle"></i> Add Question to "{{ $quiz->title }}"
                    </div>
                    <div>
                        Question {{ $quiz->questions->count() + 1 }}
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.questions.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                        <div class="mb-3">
                            <label for="question_text" class="form-label">Question Text</label>
                            <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                    id="question_text" 
                                    name="question_text" 
                                    rows="3" 
                                    required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Question Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type" 
                                    required>
                                <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>True/False</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="options-container">
                            <div class="mb-3">
                                <label for="option_a" class="form-label">Option A</label>
                                <input type="text" 
                                       class="form-control @error('option_a') is-invalid @enderror" 
                                       id="option_a" 
                                       name="option_a" 
                                       value="{{ old('option_a') }}" 
                                       required>
                                @error('option_a')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="option_b" class="form-label">Option B</label>
                                <input type="text" 
                                       class="form-control @error('option_b') is-invalid @enderror" 
                                       id="option_b" 
                                       name="option_b" 
                                       value="{{ old('option_b') }}" 
                                       required>
                                @error('option_b')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="multiple-choice-options">
                                <div class="mb-3">
                                    <label for="option_c" class="form-label">Option C</label>
                                    <input type="text" 
                                           class="form-control @error('option_c') is-invalid @enderror" 
                                           id="option_c" 
                                           name="option_c" 
                                           value="{{ old('option_c') }}">
                                    @error('option_c')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="option_d" class="form-label">Option D</label>
                                    <input type="text" 
                                           class="form-control @error('option_d') is-invalid @enderror" 
                                           id="option_d" 
                                           name="option_d" 
                                           value="{{ old('option_d') }}">
                                    @error('option_d')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="correct_option" class="form-label">Correct Answer</label>
                            <select class="form-select @error('correct_option') is-invalid @enderror" 
                                    id="correct_option" 
                                    name="correct_option" 
                                    required>
                                <option value="A" {{ old('correct_option') == 'A' ? 'selected' : '' }}>Option A</option>
                                <option value="B" {{ old('correct_option') == 'B' ? 'selected' : '' }}>Option B</option>
                                <option value="C" {{ old('correct_option') == 'C' ? 'selected' : '' }} class="multiple-choice-option">Option C</option>
                                <option value="D" {{ old('correct_option') == 'D' ? 'selected' : '' }} class="multiple-choice-option">Option D</option>
                            </select>
                            @error('correct_option')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">Points</label>
                            <input type="number" 
                                   class="form-control @error('points') is-invalid @enderror" 
                                   id="points" 
                                   name="points" 
                                   value="{{ old('points', 1) }}" 
                                   min="1" 
                                   required>
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Quiz
                            </a>
                            <div>
                                <button type="submit" name="add_another" value="1" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Save & Add Another
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Question
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        font-size: 1.1rem;
    }
    .form-label {
        font-weight: 500;
    }
    .multiple-choice-options.hidden {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const multipleChoiceOptions = document.querySelectorAll('.multiple-choice-options');
    const multipleChoiceSelects = document.querySelectorAll('.multiple-choice-option');
    const optionA = document.getElementById('option_a');
    const optionB = document.getElementById('option_b');

    function updateQuestionType() {
        const isMultipleChoice = typeSelect.value === 'multiple_choice';
        
        multipleChoiceOptions.forEach(el => {
            el.classList.toggle('hidden', !isMultipleChoice);
        });

        multipleChoiceSelects.forEach(el => {
            el.style.display = isMultipleChoice ? '' : 'none';
        });

        if (!isMultipleChoice) {
            optionA.value = optionA.value || 'True';
            optionB.value = optionB.value || 'False';
        } else {
            if (optionA.value === 'True') optionA.value = '';
            if (optionB.value === 'False') optionB.value = '';
        }
    }

    typeSelect.addEventListener('change', updateQuestionType);
    updateQuestionType();
});
</script>
@endpush 