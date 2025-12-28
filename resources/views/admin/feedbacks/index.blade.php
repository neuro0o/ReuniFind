@extends('layouts.default')

@section('title', 'Manage User Feedbacks')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/feedback/admin_feedback.css') }}">
@endsection

@section('content')
    <div class="layout">
        @include('layouts.partials.admin_sidebar')

        <div class="main-content">
            <div class="feedbacks-header">
                <h1>Manage User Feedbacks</h1>
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
                        <div class="stat-label">Total Feedbacks</div>
                        <div class="stat-value">{{ $feedbacks->total() }}</div>
                    </div>
                </div>
                <div class="stat-card pending">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <div class="stat-label">Pending</div>
                        <div class="stat-value">{{ \App\Models\Feedback::where('feedbackStatus', 'Pending')->count() }}</div>
                    </div>
                </div>
                <div class="stat-card reviewed">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <div class="stat-label">Reviewed</div>
                        <div class="stat-value">{{ \App\Models\Feedback::where('feedbackStatus', 'Reviewed')->count() }}</div>
                    </div>
                </div>
                <div class="stat-card bug">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <div class="stat-label">Bug Reports</div>
                        <div class="stat-value">{{ \App\Models\Feedback::where('feedbackType', 'Error/Bug Report')->count() }}</div>
                    </div>
                </div>
                <div class="stat-card review">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <div class="stat-label">Review</div>
                        <div class="stat-value">{{ \App\Models\Feedback::where('feedbackType', 'Review')->count() }}</div>
                    </div>
                </div>
                <div class="stat-card suggestion">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <div class="stat-label">Suggestion</div>
                        <div class="stat-value">{{ \App\Models\Feedback::where('feedbackType', 'Suggestion')->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-card">
                <form action="{{ route('admin.feedbacks') }}" method="GET" class="filters-form">
                    <div class="filter-group">
                        <label for="type">Filter by Type</label>
                        <select name="type" id="type" class="filter-select">
                            <option value="">All Types</option>
                            <option value="Error/Bug Report" {{ request('type') == 'Error/Bug Report' ? 'selected' : '' }}>
                                Error/Bug Report
                            </option>
                            <option value="Review" {{ request('type') == 'Review' ? 'selected' : '' }}>
                                Review
                            </option>
                            <option value="Suggestion" {{ request('type') == 'Suggestion' ? 'selected' : '' }}>
                                Suggestion
                            </option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="status">Filter by Status</label>
                        <select name="status" id="status" class="filter-select">
                            <option value="">All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="Reviewed" {{ request('status') == 'Reviewed' ? 'selected' : '' }}>
                                Reviewed
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">Apply Filters</button>
                    <a href="{{ route('admin.feedbacks') }}" class="btn-reset">Reset</a>
                </form>
            </div>

            <br><br>

            <!-- Feedbacks List -->
            @forelse($feedbacks as $feedback)
                <div class="feedback-item">
                    <div class="feedback-header-row">
                        <div class="feedback-badges">
                            <span class="feedback-badge 
                                @if($feedback->feedbackType == 'Error/Bug Report') badge-bug
                                @elseif($feedback->feedbackType == 'Review') badge-review
                                @else badge-suggestion
                                @endif">
                                @if($feedback->feedbackType == 'Error/Bug Report') 🐛
                                @elseif($feedback->feedbackType == 'Review') ⭐
                                @else 💡
                                @endif
                                {{ $feedback->feedbackType }}
                            </span>
                            <span class="feedback-badge {{ $feedback->feedbackStatus == 'Pending' ? 'badge-pending' : 'badge-reviewed' }}">
                                {{ $feedback->feedbackStatus }}
                            </span>
                        </div>
                        <span class="feedback-date">
                            {{ $feedback->feedbackDate->format('M d, Y - h:i A') }}
                        </span>
                    </div>

                    <div class="feedback-user-info">
                        <div class="user-avatar">
                            <img src="{{ $feedback->user->profileImg ? asset('storage/' . $feedback->user->profileImg) : asset('images/profiles/user_default.png') }}" 
                                alt="{{ $feedback->user->userName }}">
                        </div>
                        <div class="user-details">
                            <strong class="user-name">{{ $feedback->user->userName }}</strong>
                            <span class="user-email">{{ $feedback->user->userEmail }}</span>
                        </div>
                    </div>

                    <div class="feedback-text-box">
                        <p>{{ $feedback->feedbackText }}</p>
                    </div>

                    <div class="feedback-actions">
                        @if($feedback->feedbackStatus == 'Pending')
                            <form action="{{ route('admin.feedbacks.review', $feedback->feedbackID) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-review">
                                    <span>✓</span>
                                    Mark as Reviewed
                                </button>
                            </form>
                        @else
                            <button class="btn-action btn-reviewed" disabled>
                                <span>✓</span>
                                Already Reviewed
                            </button>
                        @endif

                        <form action="{{ route('admin.feedbacks.delete', $feedback->feedbackID) }}" method="POST" style="display: inline;"
                            onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">
                                <span>🗑</span>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <!-- <div class="empty-icon">📭</div> -->
                    <h3>No Feedbacks Found</h3>
                    <p>There are no feedbacks matching your current filters</p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($feedbacks->hasPages())
                <div class="pagination-container">
                    {{ $feedbacks->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('js/admin_sidebar.js') }}"></script>
@endsection
