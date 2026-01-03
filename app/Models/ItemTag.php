<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTag extends Model
{
    protected $primaryKey = 'tagID';
    public $timestamps = true;

    protected $fillable = [
        'tagImg',
        'itemName',
        'itemImg',
        'itemCategory',
        'itemDescription',
        'itemStatus',
        'userID'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Each tag belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    /**
     * Relationship: Each tag belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'itemCategory', 'categoryID');
    }

    /**
     * Scope: Get only safe items
     */
    public function scopeSafe($query)
    {
        return $query->where('itemStatus', 'Safe');
    }

    /**
     * Scope: Get only lost items
     */
    public function scopeLost($query)
    {
        return $query->where('itemStatus', 'Lost');
    }

    /**
     * Check if item is lost
     */
    public function isLost()
    {
        return $this->itemStatus === 'Lost';
    }

    /**
     * Check if item is safe
     */
    public function isSafe()
    {
        return $this->itemStatus === 'Safe';
    }

    /**
     * Mark item as lost
     */
    public function markAsLost()
    {
        $this->itemStatus = 'Lost';
        $this->save();
    }

    /**
     * Mark item as safe
     */
    public function markAsSafe()
    {
        $this->itemStatus = 'Safe';
        $this->save();
    }

    /**
     * Get the full URL for the tag scan page
     */
    public function getScanUrl()
    {
        return route('tag.info', ['tagID' => $this->tagID]);
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedDate()
    {
        return $this->created_at->format('F d, Y');
    }
}