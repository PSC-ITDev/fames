<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FixedAsset;

class AssetEvaluationDetail extends Model
{
    protected $guarded = ['id'];
    public function asset()
    {
        return $this->belongsTo(FixedAsset::class, 'asset_id', 'id');
    }
}
