<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandoverMessage extends Model
{
    use HasFactory;

    protected $table = 'handover_messages';
    protected $primaryKey = 'messageID';
    
    // Disable updated_at since we only need created_at
    public $timestamps = false;
    
    protected $fillable = [
        'requestID',
        'senderID',
        'messageText',
        'messageImg',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relationship: Message belongs to a handover request
     */
    public function handoverRequest()
    {
        return $this->belongsTo(HandoverRequest::class, 'requestID', 'requestID');
    }

    /**
     * Relationship: Message belongs to a sender (User)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'senderID', 'userID');
    }
}