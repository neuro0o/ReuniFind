@extends('layouts.default')

@section('title', 'Create FAQ')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/faq/admin_faq_form.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="main-content">
        <div class="page-header">
            <h1>Create New FAQ</h1>
        </div>

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

        <div class="form-card">
            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="faqQuestion">
                        Question <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="faqQuestion" 
                           id="faqQuestion" 
                           class="form-control" 
                           placeholder="Enter the FAQ question..."
                           value="{{ old('faqQuestion') }}"
                           required
                           maxlength="255">
                    <small class="help-text">Keep it clear and concise (max 255 characters)</small>
                    @error('faqQuestion')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="faqAnswer">
                        Answer <span class="required">*</span>
                    </label>
                    <textarea name="faqAnswer" 
                              id="faqAnswer" 
                              class="form-control" 
                              placeholder="Provide a detailed answer..."
                              required
                              rows="6">{{ old('faqAnswer') }}</textarea>
                    <small class="help-text">(max 1000 characters)</small>
                    <div class="char-counter" id="charCounter">0 characters</div>
                    @error('faqAnswer')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i> Create FAQ
                    </button>
                    <a href="{{ route('admin.faqs') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-js')
    <script src="{{ asset('js/admin_sidebar.js') }}"></script>
    <script>
        // Character counter for answer
        const answerField = document.getElementById('faqAnswer');
        const charCounter = document.getElementById('charCounter');

        function updateCharCounter() {
            const length = answerField.value.length;
            charCounter.textContent = `${length} characters`;
        }

        answerField.addEventListener('input', updateCharCounter);
        updateCharCounter();
    </script>
@endsection
