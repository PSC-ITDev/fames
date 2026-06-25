<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AssetEvaluation as Evaluation;
use App\Models\User;

class Activity extends Model
{
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'type_id', 'id');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by', 'id');
    }
}
