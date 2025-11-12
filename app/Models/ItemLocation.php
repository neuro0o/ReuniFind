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
}
