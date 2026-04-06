<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FixedAsset;

class Department extends Model
{
    protected $guarded = ['id'];
    public function fixedAssets()
    {
        return $this->hasMany(FixedAsset::class);
    }   
    
}
