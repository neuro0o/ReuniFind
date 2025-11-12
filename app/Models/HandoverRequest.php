<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandoverRequest extends Model
{
    use HasFactory;

    // Primary key
    protected $primaryKey = 'requestID';

    // Fields that can be mass-assigned
    protected $fillable = [
        'reportID',
        'recipientID',
        'senderReportID',
        'senderID',
        'requestType',
        'proofText',
        'proofImg',
        'requestStatus',
        'rejectionNote',
    ];

    /**
     * RELATIONSHIPS
     */

    // Each handover request is linked to one item report
    public function report()
    {
        return $this->belongsTo(ItemReport::class, 'reportID', 'reportID');
    }

    // The user who initiated the handover (claim/return)
    public function sender()
    {
        return $this->belongsTo(User::class, 'senderID', 'userID');
    }

    public function senderReport()
    {
        return $this->belongsTo(ItemReport::class, 'senderReportID', 'reportID');
    }

    // The user who receives the handover request
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipientID', 'userID');
    }

    // A handover request can have many chat messages
    public function messages()
    {
        return $this->hasMany(HandoverMessage::class, 'requestID', 'requestID');
    }
}
