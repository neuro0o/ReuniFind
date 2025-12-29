@extends('layouts.default')

@section('title', 'Manage FAQs')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/faq/admin_faq.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="main-content">
        <div class="page-header">
            <h1>Manage FAQs</h1>
        </div>

        @if(session('success'))
            <div class="status-info-card published">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Total FAQs</div>
                    <div class="stat-value">{{ $faqs->count() }}</div>
                </div>
            </div>
            <div class="stat-card recent">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Added This Month</div>
                    <div class="stat-value">{{ $faqs->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
                </div>
            </div>
        </div>

        <!-- FAQ Table -->
        @if($faqs->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"></div>
                <h3>No FAQs Yet</h3>
                <p>Start by creating first FAQ entry</p>
                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create First FAQ
                </a>
            </div>
        @else
            <br>
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
              <i class="fas fa-plus"></i> Add New FAQ
            </a>
            <br>
            <div class="table-container">
                <table class="faq-table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Created</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faqs as $faq)
                        <tr>
                            <td data-label="Question">
                                <div class="question-cell">{{ Str::limit($faq->faqQuestion, 80) }}</div>
                            </td>
                            <td data-label="Answer">
                                <div class="answer-cell">{{ Str::limit($faq->faqAnswer, 100) }}</div>
                            </td>
                            <td data-label="Created">
                                {{ $faq->created_at->format('M d, Y') }}
                            </td>
                            <td data-label="Last Updated">
                                {{ $faq->updated_at->diffForHumans() }}
                            </td>
                            <td data-label="Actions">
                                <div class="btn-group">
                                    <a href="{{ route('admin.faqs.edit', $faq->faqID) }}" class="btn edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.faqs.delete', $faq->faqID) }}" 
                                          method="POST" 
                                          style="display:inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this FAQ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@section('page-js')
    <script src="{{ asset('js/admin_sidebar.js') }}"></script>
@endsection
