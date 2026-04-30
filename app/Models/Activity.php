<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'type_id', 'id')->where('type','Asset Evaluation');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by', 'id');
    }
}
