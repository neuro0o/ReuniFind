@extends('layouts.default')

@section('title', 'Manage Forum Posts')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forum/admin_forum.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="main-content">
        <div class="page-header">
            <h1>Manage Forum Posts</h1>
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
                <div class="stat-info">
                    <div class="stat-label">Total Posts</div>
                    <div class="stat-value">{{ $posts->count() }}</div>
                </div>
            </div>
            <div class="stat-card story">
                <div class="stat-info">
                    <div class="stat-label">Personal Stories</div>
                    <div class="stat-value">{{ $posts->where('forumCategory', 'Personal Story')->count() }}</div>
                </div>
            </div>
            <div class="stat-card tips">   
                <div class="stat-info">
                    <div class="stat-label">Tips & Tricks</div>
                    <div class="stat-value">{{ $posts->where('forumCategory', 'Tips & Tricks')->count() }}</div>
                </div>
            </div>
            <div class="stat-card others">
                <div class="stat-info">
                    <div class="stat-label">Others</div>
                    <div class="stat-value">{{ $posts->where('forumCategory', 'Others')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="filters-card">
            <form method="GET" action="{{ route('admin.forum.posts') }}" class="filters-form">
                <div class="filter-group">
                    <label for="search">Search Posts</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           class="filter-input" 
                           placeholder="Search by title, content, or author..."
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
                    <label for="author">Filter by Author Role</label>
                    <select name="author" id="author" class="filter-select">
                        <option value="all" {{ request('author') == 'all' || !request('author') ? 'selected' : '' }}>All Posts</option>
                        <option value="admin_posts" {{ request('author') == 'admin_posts' ? 'selected' : '' }}>👑 Admin Posts</option>
                        <option value="user_posts" {{ request('author') == 'user_posts' ? 'selected' : '' }}>👤 User Posts</option>
                    </select>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('admin.forum.posts') }}" class="btn-reset">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>
        </div>

        <!-- Forum Posts Table -->
        @if($posts->isEmpty())
            <div class="empty-state">
                <h3>No Forum Posts</h3>
                <p>
                    @if(request('search') || request('category') != 'all' || request('author') != 'all')
                        No posts match your current filters.
                    @else
                        No forum posts have been created yet.
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
            <div class="table-container">
                <table class="forum-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Likes</th>
                            <th>Comments</th>
                            <th>Posted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td data-label="No.">{{ $loop->iteration }}.</td>
                            <td data-label="Title">
                                <div class="title-cell">{{ Str::limit($post->forumTitle, 50) }}</div>
                            </td>
                            
                            <td data-label="Author">
                                <div class="author-cell">
                                    <img src="{{ $post->user->profileImg 
                                        ? asset('storage/' . $post->user->profileImg) 
                                        : asset($post->user->userRole === 'Admin' 
                                            ? 'images/profiles/admin_default.png' 
                                            : 'images/profiles/user_default.png') }}" 
                                        alt="{{ $post->user->userName }}"
                                        class="table-avatar"><br>
                                    {{ $post->user->userName }}
                                    @if($post->user->userRole === 'Admin')
                                        <span style="color: #fbbf24;">👑</span>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Category">
                                <span class="category-badge {{ strtolower(str_replace(' ', '-', $post->forumCategory)) }}">
                                    {{ $post->forumCategory }}
                                </span>
                            </td>
                            <td data-label="Likes">
                                <span class="likes-count">
                                    <i class="fas fa-thumbs-up"></i> {{ $post->likesCount() }}
                                    <i class="fas fa-thumbs-down"></i> {{ $post->dislikesCount() }}
                                </span>
                            </td>
                            <td data-label="Comments">
                                {{ $post->comments->count() }}
                            </td>
                            <td data-label="Posted">
                                {{ $post->created_at->format('M d, Y') }}
                            </td>
                            <td data-label="Actions">
                                <div class="btn-group">
                                    <a href="{{ route('forum.show', $post->forumID) }}" class="btn view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <form action="{{ route('admin.forum.delete', $post->forumID) }}" 
                                          method="POST" 
                                          style="display:inline;" 
                                          onsubmit="return confirm('Delete this forum post? This will also delete all comments.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
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
