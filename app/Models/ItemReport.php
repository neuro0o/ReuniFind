<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemReport extends Model
{
    protected $primaryKey = 'reportID';
    public $timestamps = false;

    protected $fillable = [
        'reportType',
        'reportDate',
        'reportStatus',
        'itemName',
        'itemCategory',
        'itemDescription',
        'itemLocation',
        'itemImg',
        'verificationNote',
        'verificationImg',
        'rejectionNote',
        'userID'
    ];

    protected $casts = [
        'reportDate' => 'datetime',
    ];


    // Each report belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID'); 
        // first 'userID' = FK in this table
        // second 'userID' = PK in users table
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'itemCategory', 'categoryID');
    }

    public function location()
    {
        return $this->belongsTo(ItemLocation::class, 'itemLocation', 'locationID');
    }

    public function sentHandoverRequests()
    {
        return $this->hasMany(HandoverRequest::class, 'senderReportID', 'reportID');
    }

    public function receivedHandoverRequests()
    {
        return $this->hasMany(HandoverRequest::class, 'reportID', 'reportID');
    }


    /**
     * Get all handover requests associated with this item report.
     * Optionally filter by sender and recipient.
     */
    public function handoverRequests(?int $senderID = null, ?int $recipientID = null)
    {
        $query = \App\Models\HandoverRequest::query()
            ->where(function ($q) {
                $q->where('senderReportID', $this->reportID)
                ->orWhere('reportID', $this->reportID);
            });

        if ($senderID) {
            $query->where('senderID', $senderID);
        }

        if ($recipientID) {
            $query->where('recipientID', $recipientID);
        }

        return $query->get();
    }


    // ---------------- MATCHING & HANDOVER HELPERS ---------------- //
    /**
     * Check if this report is of opposite type compared to another report.
     */
    public function isOppositeType(ItemReport $otherReport)
    {
        return ($this->reportType === 'Lost' && $otherReport->reportType === 'Found')
            || ($this->reportType === 'Found' && $otherReport->reportType === 'Lost');
    }

    /**
     * Check if this report has been dismissed by a specific user for a specific other report.
     */
    public function isDismissedForUserAndReport(int $userID, int $otherReportID)
    {
        return \App\Models\MatchSuggestion::where('reportID', $this->reportID)
            ->where('matchedReportID', $otherReportID)
            ->where('userID', $userID)
            ->where('matchStatus', 'dismissed')
            ->exists();
    }

    /**
     * Check if this report is already used in any active handover
     * specifically between the current sender (userID) and recipient (otherReport's owner)
     */
    public function isUsedForHandoverWith(ItemReport $otherReport, int $currentUserID): bool
    {
        return \App\Models\HandoverRequest::whereIn('requestStatus', ['Pending', 'Approved', 'Completed'])
            ->where('senderID', $currentUserID)
            ->where('recipientID', $otherReport->userID)
            ->where(function($q) use ($otherReport) {
                $q->where('senderReportID', $this->reportID)
                ->orWhere('reportID', $this->reportID)
                ->orWhere('senderReportID', $otherReport->reportID)
                ->orWhere('reportID', $otherReport->reportID);
            })
            ->exists();
    }


    /**
     * Determine if this report is eligible to be paired with another report for handover
     */
    public function canHandoverWith(ItemReport $otherReport, int $currentUserID)
    {
        return $this->isOppositeType($otherReport)
            && !$this->isDismissedForUserAndReport($currentUserID, $otherReport->reportID)
            && !$this->isUsedForHandoverWith($otherReport, $currentUserID);
    }
}
