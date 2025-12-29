<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $table = 'forum_posts';
    protected $primaryKey = 'forumID';

    protected $fillable = [
        'forumCategory',
        'forumTitle',
        'forumContent',
        'forumImg',
        'forumDate',
        'userID',
    ];

    protected $casts = [
        'forumDate' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Forum post belongs to a user (author)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    /**
     * Relationship: Forum post has many comments
     */
    public function comments()
    {
        return $this->hasMany(ForumComment::class, 'forumID', 'forumID')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Relationship: Forum post has many likes
     */
    public function likes()
    {
        return $this->hasMany(ForumLike::class, 'forumID', 'forumID');
    }

    /**
     * Get total likes count
     */
    public function likesCount()
    {
        return $this->likes()->where('likeType', 'like')->count();
    }

    /**
     * Get total dislikes count
     */
    public function dislikesCount()
    {
        return $this->likes()->where('likeType', 'dislike')->count();
    }

    /**
     * Get net likes (likes - dislikes)
     */
    public function netLikes()
    {
        return $this->likesCount() - $this->dislikesCount();
    }

    /**
     * Check if current user has liked this post
     */
    public function isLikedBy($userID)
    {
        return $this->likes()
            ->where('userID', $userID)
            ->where('likeType', 'like')
            ->exists();
    }

    /**
     * Check if current user has disliked this post
     */
    public function isDislikedBy($userID)
    {
        return $this->likes()
            ->where('userID', $userID)
            ->where('likeType', 'dislike')
            ->exists();
    }

    /**
     * Get user's reaction (like/dislike/null)
     */
    public function userReaction($userID)
    {
        $like = $this->likes()->where('userID', $userID)->first();
        return $like ? $like->likeType : null;
    }
}
