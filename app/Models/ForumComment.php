<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use HasFactory;

    protected $table = 'forum_comments';
    protected $primaryKey = 'commentID';

    protected $fillable = [
        'forumID',
        'userID',
        'commentText',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Comment belongs to a forum post
     */
    public function forumPost()
    {
        return $this->belongsTo(ForumPost::class, 'forumID', 'forumID');
    }

    /**
     * Relationship: Comment belongs to a user (author)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
