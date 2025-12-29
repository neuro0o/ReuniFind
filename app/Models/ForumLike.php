<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumLike extends Model
{
    use HasFactory;

    protected $table = 'forum_likes';
    protected $primaryKey = 'likeID';

    protected $fillable = [
        'forumID',
        'userID',
        'likeType',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Like belongs to a forum post
     */
    public function forumPost()
    {
        return $this->belongsTo(ForumPost::class, 'forumID', 'forumID');
    }

    /**
     * Relationship: Like belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
