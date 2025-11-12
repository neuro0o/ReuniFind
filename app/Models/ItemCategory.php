<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $primaryKey = 'categoryID';
    public $timestamps = false;

    protected $fillable = [
        'categoryName',
        'description',
    ];

    // Optional: for reverse relation
    public function itemReports()
    {
        return $this->hasMany(ItemReport::class, 'itemCategory', 'categoryID');
    }
}
