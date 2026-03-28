<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FixedAsset;

class AssetClassification extends Model
{
    protected $guarded = ['id'];    
    
    public function classification()
    {
        return $this->hasOne(FixedAsset::class);
    }  
}
