<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FixedAsset;
use App\Models\AssetEvaluation as Evaluation;

class Department extends Model
{
    protected $guarded = ['id'];
    public function fixedAssets()
    {
        return $this->hasOne(FixedAsset::class);
    }  
    
    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }   
    
}
