<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchSuggestion extends Model
{
    use HasFactory;

    protected $primaryKey = 'suggestionID';

    protected $fillable = [
        'reportID',
        'matchedReportID',
        'userID',
        'matchStatus',
        'matchedAt',
    ];

    public $timestamps = true;

    // Relationships
    public function report()
    {
        return $this->belongsTo(ItemReport::class, 'reportID', 'reportID');
    }

    public function matchedReport()
    {
        return $this->belongsTo(ItemReport::class, 'matchedReportID', 'reportID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function isCompleted(): bool
    {
        return $this->matchStatus === 'completed';
    }
}