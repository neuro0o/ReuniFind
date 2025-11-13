<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemLocation extends Model
{
    protected $primaryKey = 'locationID';
    public $timestamps = false;

    protected $fillable = [
        'locationName',
        'latitude',
        'longitude',
    ];

    // Optional: for reverse relation
    public function itemReports()
    {
        return $this->hasMany(ItemReport::class, 'itemLocation', 'locationID');
    }

    // Optional: convenience for Lost reports
    public function lostReports()
    {
        return $this->itemReports()->where('reportType', 'Lost');
    }

    // Optional: convenience for Found reports
    public function foundReports()
    {
        return $this->itemReports()->where('reportType', 'Found');
    }
}
