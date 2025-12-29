@extends('layouts.default')

@section('title', 'Community Forum')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    @if(Auth::user()->userRole === 'Admin')
        <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/forum/forum_index.css') }}">
@endsection

@section('content')
<div class="layout">
    @if(Auth::user()->userRole === 'Admin')
        @include('layouts.partials.admin_sidebar')
    @else
        @include('layouts.partials.sidebar')
    @endif

    <div class="main-content">
        <div class="page-header">
            <h1>Community Forum</h1>
        </div>

        @if(session('success'))
            <div class="status-info-card published">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filters & Search -->
        <div class="filters-card">
            <form method="GET" action="{{ route('forum.index') }}" class="filters-form">
                <div class="filter-group">
                    <label for="search">Search Posts</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           class="filter-input" 
                           placeholder="Search by title or content..."
                           value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <label for="category">Filter by Category</label>
                    <select name="category" id="category" class="filter-select">
                        <option value="all" {{ request('category') == 'all' || !request('category') ? 'selected' : '' }}>All Categories</option>
                        <option value="Personal Story" {{ request('category') == 'Personal Story' ? 'selected' : '' }}>📖 Personal Story</option>
                        <option value="Tips & Tricks" {{ request('category') == 'Tips & Tricks' ? 'selected' : '' }}>💡 Tips & Tricks</option>
                        <option value="Others" {{ request('category') == 'Others' ? 'selected' : '' }}>💬 Others</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="author">Filter by Author</label>
                    <select name="author" id="author" class="filter-select">
                        <option value="all" {{ request('author') == 'all' || !request('author') ? 'selected' : '' }}>All Posts</option>
                        <option value="my_posts" {{ request('author') == 'my_posts' ? 'selected' : '' }}>My Posts Only</option>
                        <option value="admin_posts" {{ request('author') == 'admin_posts' ? 'selected' : '' }}>Admin Posts</option>
                    </select>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('forum.index') }}" class="btn-reset">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>
        </div>

        <!-- Forum Posts Grid -->
        @if($posts->isEmpty())
            <div class="empty-state">
                <h3>No Posts Found</h3>
                <p>
                    @if(request('author') == 'my_posts')
                        You haven't created any posts yet.
                    @elseif(request('author') == 'admin_posts')
                        No admin announcements at this time.
                    @elseif(request('search') || request('category') != 'all')
                        No posts match your current filters.
                    @else
                        Be the first to create a forum post!
                    @endif
                </p>
                <br><br>
                <a href="{{ route('forum.create') }}" class="btn-create">
                    <i class="fas fa-plus"></i> Create Post
                </a>
            </div>
        @else
            <a href="{{ route('forum.create') }}" class="btn-create">
                <i class="fas fa-plus"></i> Create Post
            </a>

            <h5>_</h5>

            <div class="posts-container">
                @foreach($posts as $post)
                    <div class="post-card">
                        <div class="post-header">
                            <div class="author-info">
                                <img src="{{ $post->user->profileImg 
                                    ? asset('storage/' . $post->user->profileImg) 
                                    : asset($post->user->userRole === 'Admin' 
                                        ? 'images/profiles/admin_default.png' 
                                        : 'images/profiles/user_default.png') }}" 
                                    alt="{{ $post->user->userName }}" 
                                    class="author-avatar">
                                <div class="author-details">
                                    <h4>{{ $post->user->userName }}</h4>
                                    <span class="post-date">{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <span class="category-badge {{ strtolower(str_replace(' ', '-', $post->forumCategory)) }}">
                                {{ $post->forumCategory }}
                            </span>
                        </div>

                        <div class="post-content">
                            <h3>{{ $post->forumTitle }}</h3>
                            <p>{{ Str::limit($post->forumContent, 200) }}</p>
                            @if($post->forumImg)
                                <img src="{{ asset('storage/' . $post->forumImg) }}" 
                                     alt="Post image" 
                                     class="post-image">
                            @endif
                        </div>

                        <div class="post-footer">
                            <div class="post-stats">
                                <span class="stat">
                                    <i class="fas fa-thumbs-up"></i> {{ $post->likesCount() }}
                                </span>
                                <span class="stat">
                                    <i class="fas fa-thumbs-down"></i> {{ $post->dislikesCount() }}
                                </span>
                                <span class="stat">
                                    <i class="fas fa-comment"></i> {{ $post->comments->count() }}
                                </span>
                            </div>

                            <div class="post-actions">
                                <a href="{{ route('forum.show', $post->forumID) }}" class="btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                
                                @if(Auth::id() === $post->userID)
                                    <a href="{{ route('forum.edit', $post->forumID) }}" class="btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('forum.destroy', $post->forumID) }}" 
                                          method="POST" 
                                          style="display:inline;" 
                                          onsubmit="return confirm('Delete this post? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('page-js')
    @if(Auth::user()->userRole === 'Admin')
        <script src="{{ asset('js/admin_sidebar.js') }}"></script>
    @else
        <script src="{{ asset('js/sidebar.js') }}"></script>
    @endif
@endsection