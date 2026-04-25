<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\User;
use App\Models\AssetEvaluationDetail as EvaluationDetails;

class AssetEvaluation extends Model
{
    protected $guarded = ['id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function details()
    {
        return $this->hasMany(EvaluationDetails::class, 'asset_form_id', 'id');
    }

    public function details_writtenOff()
    {
        return $this->hasMany(EvaluationDetails::class, 'asset_form_id', 'id')->where('iswrite_off',1);
    }

    public function details_remaining()
    {
        return $this->hasMany(EvaluationDetails::class, 'asset_form_id', 'id')->where('iswrite_off',0);
    }
   
}
