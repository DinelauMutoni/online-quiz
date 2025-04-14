@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-question-circle"></i> {{ $quiz->title }}
                    </div>
                    <div id="timer" class="bg-white text-primary px-3 py-1 rounded">
                        <i class="fas fa-clock"></i> <span id="time-remaining">{{ $quiz->time_limit }}:00</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="progress mb-4">
                        <div class="progress-bar" role="progressbar" style="width: {{ ($currentQuestionIndex / $quiz->questions->count()) * 100 }}%">
                            Question {{ $currentQuestionIndex + 1 }} of {{ $quiz->questions->count() }}
                        </div>
                    </div>

                    <h5 class="card-title mb-4">{{ $currentQuestion->question_text }}</h5>
                    <form action="{{ route('student.quiz.submit', ['quizAttempt' => $quizAttempt->id]) }}" method="POST" id="quiz-form">
                        @csrf
                        <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                        
                        <div class="answer-options">
                            <div class="answer-option">
                                <input type="radio" name="selected_option" id="option_a" value="A" required>
                                <label for="option_a" class="w-100">
                                    <span class="option-letter">A</span>
                                    <span class="option-text">{{ $currentQuestion->option_a }}</span>
                                </label>
                            </div>
                            
                            <div class="answer-option">
                                <input type="radio" name="selected_option" id="option_b" value="B" required>
                                <label for="option_b" class="w-100">
                                    <span class="option-letter">B</span>
                                    <span class="option-text">{{ $currentQuestion->option_b }}</span>
                                </label>
                            </div>
                            
                            @if($currentQuestion->type === 'multiple_choice' && $currentQuestion->option_c)
                            <div class="answer-option">
                                <input type="radio" name="selected_option" id="option_c" value="C" required>
                                <label for="option_c" class="w-100">
                                    <span class="option-letter">C</span>
                                    <span class="option-text">{{ $currentQuestion->option_c }}</span>
                                </label>
                            </div>
                            @endif
                            
                            @if($currentQuestion->type === 'multiple_choice' && $currentQuestion->option_d)
                            <div class="answer-option">
                                <input type="radio" name="selected_option" id="option_d" value="D" required>
                                <label for="option_d" class="w-100">
                                    <span class="option-letter">D</span>
                                    <span class="option-text">{{ $currentQuestion->option_d }}</span>
                                </label>
                            </div>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Submit Answer</button>
                            <div class="progress-text">
                                Question {{ $currentQuestionIndex + 1 }} of {{ $quiz->questions->count() }}
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
    .answer-option {
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        transition: all 0.2s ease-in-out;
    }
    .answer-option:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .progress {
        height: 1.5rem;
    }
    .progress-bar {
        background-color: #007bff;
        transition: width 0.3s ease-in-out;
    }
    #timer {
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let timeLimit = {{ $quiz->time_limit * 60 }};
    const timerDisplay = document.getElementById('time-remaining');
    const quizForm = document.getElementById('quiz-form');

    const timer = setInterval(function() {
        timeLimit--;
        const minutes = Math.floor(timeLimit / 60);
        const seconds = timeLimit % 60;
        timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLimit <= 0) {
            clearInterval(timer);
            quizForm.submit();
        }
    }, 1000);

    // Make entire answer option clickable
    document.querySelectorAll('.answer-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
});
</script>
@endpush 