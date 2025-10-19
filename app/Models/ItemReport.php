<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemReport extends Model
{
    protected $primaryKey = 'reportID';
    public $timestamps = false;

    protected $fillable = [
        'reportType',
        'itemName',
        'itemCategory',
        'itemDescription',
        'itemLocation',
        'reportDate',
        'itemImg',
        'userID'
    ];

    // Each report belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID'); 
        // first 'userID' = FK in this table
        // second 'userID' = PK in users table
    }
}
