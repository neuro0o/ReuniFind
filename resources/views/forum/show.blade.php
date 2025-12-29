@extends('layouts.default')

@section('title', $post->forumTitle)

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    @if(Auth::user()->userRole === 'Admin')
        <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/forum/forum_show.css') }}">
@endsection

@section('content')
<div class="layout">
    @if(Auth::user()->userRole === 'Admin')
        @include('layouts.partials.admin_sidebar')
    @else
        @include('layouts.partials.sidebar')
    @endif
    
    <div class="main-content">
        <!-- Back Button -->
        @if(Auth::user()->userRole === 'Admin')
            <br><br>
        @else
            <a href="{{ route('forum.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Forum
            </a>
        @endif
        
        @if(session('success'))
            <div class="status-info-card published">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Post Detail Card -->
        <div class="post-detail-card">
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
                        <h4>
                            {{ $post->user->userName }}
                            @if($post->user->userRole === 'Admin')
                                <span style="color: #fbbf24;">👑</span>
                            @endif
                        </h4>
                        <span class="post-date">{{ $post->created_at->format('M d, Y \a\t h:i A') }}</span>
                    </div>
                </div>
                <span class="category-badge {{ strtolower(str_replace(' ', '-', $post->forumCategory)) }}">
                    {{ $post->forumCategory }}
                </span>
            </div>
            
            <h1 class="post-title">{{ $post->forumTitle }}</h1>
            
            <div class="post-content">
                <p>{!! nl2br(e($post->forumContent)) !!}</p>
                @if($post->forumImg)
                    <img src="{{ asset('storage/' . $post->forumImg) }}" alt="Post image" class="post-image">
                @endif
            </div>
            
            <div class="post-reactions">
                <div class="reaction-buttons">
                    <form action="{{ route('forum.like', $post->forumID) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="type" value="like">
                        <button type="submit" class="btn-reaction btn-like {{ $post->userReaction(Auth::id()) === 'like' ? 'active' : '' }}">
                            <i class="fas fa-thumbs-up"></i> Like ({{ $post->likesCount() }})
                        </button>
                    </form>
                    
                    <form action="{{ route('forum.like', $post->forumID) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="type" value="dislike">
                        <button type="submit" class="btn-reaction btn-dislike {{ $post->userReaction(Auth::id()) === 'dislike' ? 'active' : '' }}">
                            <i class="fas fa-thumbs-down"></i> Dislike ({{ $post->dislikesCount() }})
                        </button>
                    </form>
                </div>

                @if(Auth::id() === $post->userID)
                    <div class="post-owner-actions">
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
                    </div>
                @endif
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <h3>Discussion ({{ $post->comments->count() }} {{ $post->comments->count() == 1 ? 'comment' : 'comments' }})</h3>
            
            <!-- Add Comment Form -->
            <form action="{{ route('forum.comment.add', $post->forumID) }}" method="POST" class="comment-form">
                @csrf
                <textarea name="commentText" 
                          placeholder="Write a comment..." 
                          required 
                          maxlength="500"></textarea>
                <button type="submit" class="btn-comment">
                    <!-- <i class="fas fa-paper-plane"></i> -->
                    Post Comment
                </button>
            </form>

            <!-- Comments List -->
            <div class="comments-list">
                @forelse($post->comments as $comment)
                    <div class="comment">
                        <img src="{{ $comment->user->profileImg 
                            ? asset('storage/' . $comment->user->profileImg) 
                            : asset($comment->user->userRole === 'Admin' 
                                ? 'images/profiles/admin_default.png' 
                                : 'images/profiles/user_default.png') }}" 
                            alt="{{ $comment->user->userName }}" 
                            class="comment-avatar">
                        
                        <div class="comment-content">
                            <div class="comment-header">
                                <strong>
                                    {{ $comment->user->userName }}
                                    @if($comment->user->userRole === 'Admin')
                                        <span style="color: #fbbf24;">👑</span>
                                    @endif
                                </strong>
                                
                                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                                    <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                    @if(Auth::id() === $comment->userID || Auth::user()->userRole === 'Admin')
                                        <form action="{{ route('forum.comment.delete', $comment->commentID) }}" 
                                            method="POST" 
                                            style="display:inline;" 
                                            onsubmit="return confirm('Delete this comment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete-comment">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            <p>{{ $comment->commentText }}</p>
                        </div>
                    </div>
                @empty
                    <div class="no-comments">
                        <p>No comments yet. Be the first to share your thoughts!</p>
                    </div>
                @endforelse
            </div>
        </div>
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