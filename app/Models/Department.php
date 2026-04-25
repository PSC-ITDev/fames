<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FixedAsset;
use App\Models\AssetEvaluation as Evaluation;
use App\Models\User;

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

    public function user()
    {
        return $this->hasOne(User::class,'deptid','id');
    }

    public function hierarchy()
    {
        return $this->hasMany(ApprovalHierarchy::class, 'deptid', 'id');
    }

    public function drafter()
    {
        return $this->hasMany(ApprovalHierarchy::class, 'deptid', 'id')->where('type',1);
    }  

    public function approver()
    {
        return $this->hasMany(ApprovalHierarchy::class, 'deptid', 'id')->where('type',2);
    }  

    public function confirmer()
    {
        return $this->hasMany(ApprovalHierarchy::class, 'deptid', 'id')->where('type',3);
    }  

    
    
}
