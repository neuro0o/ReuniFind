<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';
    protected $primaryKey = 'feedbackID';

    protected $fillable = [
        'feedbackType',
        'feedbackStatus',
        'feedbackText',
        'feedbackDate',
        'userID',
    ];

    protected $casts = [
        'feedbackDate' => 'datetime',
    ];

    /**
     * Relationship: Feedback belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}