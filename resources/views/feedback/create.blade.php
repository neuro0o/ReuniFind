@extends('layouts.default')

@section('title', 'Submit Feedback')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/feedback/feedback_form.css') }}">
@endsection

@section('content')
    <div class="layout">
        @include('layouts.partials.sidebar')

        <div class="main-content">
            <div class="feedback-header">
                <h1>Submit Feedback</h1>
                <p>Help us improve ReuniFind by sharing your thoughts, reporting issues, or suggesting new features</p>
            </div>

            @if(session('success'))
                <div class="status-info-card published">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="status-info-card rejected">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul style="margin: 0.5rem 0 0 1.5rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="feedback-card">
                <form action="{{ route('feedback.store') }}" method="POST" id="feedbackForm">
                    @csrf

                    <div class="form-group">
                        <label for="feedbackType">
                            Feedback Type <span class="required">*</span>
                        </label>
                        <select name="feedbackType" id="feedbackType" class="form-control" required>
                            <option value="">-- Select Feedback Type --</option>
                            <option value="Error/Bug Report" {{ old('feedbackType') == 'Error/Bug Report' ? 'selected' : '' }}>
                                🐛 Error/Bug Report
                            </option>
                            <option value="Review" {{ old('feedbackType') == 'Review' ? 'selected' : '' }}>
                                ⭐ Review
                            </option>
                            <option value="Suggestion" {{ old('feedbackType') == 'Suggestion' ? 'selected' : '' }}>
                                💡 Suggestion
                            </option>
                        </select>
                        <p class="type-description" id="typeDescription"></p>
                        @error('feedbackType')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="feedbackText">
                            Your Feedback <span class="required">*</span>
                        </label>
                        <textarea 
                            name="feedbackText" 
                            id="feedbackText" 
                            class="form-control" 
                            placeholder="Please provide detailed feedback here..."
                            required
                            minlength="10"
                            maxlength="255"
                        >{{ old('feedbackText') }}</textarea>
                        <div class="char-counter" id="charCounter">0 / 255 characters</div>
                        @error('feedbackText')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        Submit Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        // Character counter
        const feedbackText = document.getElementById('feedbackText');
        const charCounter = document.getElementById('charCounter');

        function updateCharCounter() {
            const length = feedbackText.value.length;
            charCounter.textContent = `${length} / 255 characters`;
            
            if (length > 900) {
                charCounter.classList.add('error');
                charCounter.classList.remove('warning');
            } else if (length > 800) {
                charCounter.classList.add('warning');
                charCounter.classList.remove('error');
            } else {
                charCounter.classList.remove('warning', 'error');
            }
        }

        feedbackText.addEventListener('input', updateCharCounter);
        updateCharCounter();

        // Feedback type descriptions
        const feedbackType = document.getElementById('feedbackType');
        const typeDescription = document.getElementById('typeDescription');

        const descriptions = {
            'Error/Bug Report': 'Report technical issues, errors, or bugs you encountered while using ReuniFind.',
            'Review': 'Share your overall experience and thoughts about using the platform.',
            'Suggestion': 'Suggest new features, improvements, or changes you\'d like to see.'
        };

        feedbackType.addEventListener('change', function() {
            typeDescription.textContent = descriptions[this.value] || '';
        });

        // Trigger description on page load if value exists
        if (feedbackType.value) {
            typeDescription.textContent = descriptions[feedbackType.value] || '';
        }
    </script>
@endsection
