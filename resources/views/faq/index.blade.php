@extends('layouts.default')

@section('title', 'FAQs - Help Center')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/faq/faq_user.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.sidebar')

    <div class="main-content">
        <div class="page-header">
            <h1>Frequently Asked Questions</h1>
            <p>Find answers to common questions about ReuniFind</p>
        </div>

        <!-- Search Bar -->
        <div class="search-card">
            <form method="GET" action="{{ route('faq.index') }}" class="search-form">
                <div class="search-group">
                    <input type="text" 
                          name="search" 
                          id="search" 
                          class="search-input" 
                          placeholder="Search for questions or answers..."
                          value="{{ request('search') }}" />
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('faq.index') }}" class="btn-clear">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Results Info -->
        @if(request('search'))
            <div class="results-info">
                <p>
                    @if($faqs->count() > 0)
                        Found <strong>{{ $faqs->count() }}</strong> result(s) for "<strong>{{ request('search') }}</strong>"
                    @else
                        No results found for "<strong>{{ request('search') }}</strong>"
                    @endif
                </p>
            </div>
        @endif

        <!-- FAQ Accordion -->
        @if($faqs->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"></div>
                <h3>No FAQs Found</h3>
                <p>
                    @if(request('search'))
                        Try searching with different keywords
                    @else
                        No FAQs are available at the moment
                    @endif
                </p>
            </div>
        @else
            <div class="faq-container">
                @foreach($faqs as $index => $faq)
                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFAQ({{ $index }})">
                            <span class="question-text">{{ $faq->faqQuestion }}</span>
                            <span class="faq-icon" id="icon-{{ $index }}">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </button>
                        <div class="faq-answer" id="answer-{{ $index }}">
                            <p>{!! nl2br(e($faq->faqAnswer)) !!}</p>
                            <small class="faq-date">Last updated: {{ $faq->updated_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('page-js')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        function toggleFAQ(index) {
            const answer = document.getElementById(`answer-${index}`);
            const icon = document.getElementById(`icon-${index}`);
            const allAnswers = document.querySelectorAll('.faq-answer');
            const allIcons = document.querySelectorAll('.faq-icon');

            // Close all other FAQs
            allAnswers.forEach((item, i) => {
                if (i !== index) {
                    item.classList.remove('active');
                }
            });

            allIcons.forEach((item, i) => {
                if (i !== index) {
                    item.classList.remove('rotate');
                }
            });

            // Toggle current FAQ
            answer.classList.toggle('active');
            icon.classList.toggle('rotate');
        }

        // Auto-expand first FAQ if not searching
        @if(!request('search') && $faqs->count() > 0)
            toggleFAQ(0);
        @endif
    </script>
@endsection