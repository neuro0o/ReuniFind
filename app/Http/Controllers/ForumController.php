<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ForumController extends Controller
{
    /**
     * Display forum posts (sorted by trending - net likes)
     */
    public function index(Request $request)
    {
        $query = ForumPost::with(['user', 'comments', 'likes']);

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('forumCategory', $request->category);
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('forumTitle', 'like', '%' . $searchTerm . '%')
                  ->orWhere('forumContent', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get all posts
        $posts = $query->get();

        // Sort by net likes (trending)
        $posts = $posts->sortByDesc(function ($post) {
            return $post->netLikes();
        });

        return view('forum.index', compact('posts'));
    }

    /**
     * Show create forum form
     */
    public function create()
    {
        return view('forum.create');
    }

    /**
     * Store new forum post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'forumCategory' => 'required|in:Personal Story,Tips & Tricks,Others',
            'forumTitle' => 'required|string|max:255',
            'forumContent' => 'required|string|max:1000',
            'forumImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('forumImg')) {
            $imagePath = $request->file('forumImg')->store('images/forum', 'public');
        }

        ForumPost::create([
            'forumCategory' => $validated['forumCategory'],
            'forumTitle' => $validated['forumTitle'],
            'forumContent' => $validated['forumContent'],
            'forumImg' => $imagePath,
            'forumDate' => Carbon::now(),
            'userID' => Auth::id(),
        ]);

        return redirect()->route('forum.index')
            ->with('success', 'Forum post created successfully!');
    }

    /**
     * Show single forum post with comments
     */
    public function show($id)
    {
        $post = ForumPost::with(['user', 'comments.user', 'likes'])->findOrFail($id);
        
        return view('forum.show', compact('post'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $post = ForumPost::findOrFail($id);

        // Only author can edit
        if ($post->userID !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('forum.edit', compact('post'));
    }

    /**
     * Update forum post
     */
    public function update(Request $request, $id)
    {
        $post = ForumPost::findOrFail($id);

        // Only author can update
        if ($post->userID !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'forumCategory' => 'required|in:Personal Story,Tips & Tricks,Others',
            'forumTitle' => 'required|string|max:255',
            'forumContent' => 'required|string|max:1000',
            'forumImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('forumImg')) {
            // Delete old image
            if ($post->forumImg && Storage::disk('public')->exists($post->forumImg)) {
                Storage::disk('public')->delete($post->forumImg);
            }
            $post->forumImg = $request->file('forumImg')->store('images/forum', 'public');
        }

        $post->update([
            'forumCategory' => $validated['forumCategory'],
            'forumTitle' => $validated['forumTitle'],
            'forumContent' => $validated['forumContent'],
        ]);

        return redirect()->route('forum.show', $post->forumID)
            ->with('success', 'Forum post updated successfully!');
    }

    /**
     * Delete forum post (own posts only)
     */
    public function destroy($id)
    {
        $post = ForumPost::findOrFail($id);

        // Only author can delete
        if ($post->userID !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image
        if ($post->forumImg && Storage::disk('public')->exists($post->forumImg)) {
            Storage::disk('public')->delete($post->forumImg);
        }

        $post->delete();

        return redirect()->route('forum.index')
            ->with('success', 'Forum post deleted successfully!');
    }

    /**
     * Add comment to forum post
     */
    public function addComment(Request $request, $id)
    {
        $validated = $request->validate([
            'commentText' => 'required|string|max:500',
        ]);

        ForumComment::create([
            'forumID' => $id,
            'userID' => Auth::id(),
            'commentText' => $validated['commentText'],
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Delete comment (own comments only OR admin)
     */
    public function deleteComment($id)
    {
        $comment = ForumComment::findOrFail($id);

        // Only comment author OR admin can delete
        if ($comment->userID !== Auth::id() && Auth::user()->userRole !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    /**
     * Toggle like/dislike
     */
    public function toggleLike(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:like,dislike',
        ]);

        $existingLike = ForumLike::where('forumID', $id)
            ->where('userID', Auth::id())
            ->first();

        if ($existingLike) {
            // If same type, remove it (toggle off)
            if ($existingLike->likeType === $validated['type']) {
                $existingLike->delete();
            } else {
                // If different type, update it
                $existingLike->update(['likeType' => $validated['type']]);
            }
        } else {
            // Create new like/dislike
            ForumLike::create([
                'forumID' => $id,
                'userID' => Auth::id(),
                'likeType' => $validated['type'],
            ]);
        }

        return redirect()->back();
    }
}
