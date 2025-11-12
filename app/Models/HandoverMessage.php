<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandoverMessage extends Model
{
    use HasFactory;

    // Primary key
    protected $primaryKey = 'messageID';

    // Fields that can be mass-assigned
    protected $fillable = [
        'requestID',
        'senderID',
        'message',
        'messageImage',
    ];

    /**
     * RELATIONSHIPS
     */

    // Each message belongs to a specific handover request
    public function request()
    {
        return $this->belongsTo(HandoverRequest::class, 'requestID', 'requestID');
    }

    // Each message is sent by a user
    public function sender()
    {
        return $this->belongsTo(User::class, 'senderID', 'userID');
    }
}
