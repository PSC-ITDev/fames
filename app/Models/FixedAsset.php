<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\AssetClassification as Classification;
use App\Models\AssetLocation as Location;
use App\Models\AssetCategory as Category;

class FixedAsset extends Model
{
    protected $guarded = ['id'];



    // public function department()
    // {
    //     return $this->hasOne(Department::class);
    // }

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
