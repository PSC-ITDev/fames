<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FixedAsset;
use App\Models\AssetEvaluation as Evaluation;

class AssetEvaluationDetail extends Model
{
    protected $guarded = ['id'];
    public function asset()
    {
        return $this->belongsTo(FixedAsset::class, 'asset_id', 'id');
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'asset_form_id', 'id');
    }
}
