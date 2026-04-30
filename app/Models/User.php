<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\AssetEvaluation as Evaluation;
use App\Models\Role;
use App\Models\Department;
use App\Models\ApprovalHierarchy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Activity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;



    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
public function scopeWithRole($query, $roleName)
{
    return $query->whereHas('role', function ($q) use ($roleName) {
        $q->where('name', $roleName);
    });
}



    
    public function evaluation()
    {
        return $this->hasOne(Evaluation::class, 'created_by', 'id');
    }   



    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'deptid','id');
    }  

    public function hierarchy()
    {
        return $this->hasMany(ApprovalHierarchy::class, 'user_id', 'id');
    }  

    public function approver_user()
    {
        return $this->hasMany(ApprovalHierarchy::class, 'user_id', 'id')->where('type',2);
    }  

    public function confirmer_user()
    {
        return $this->hasMany(ApprovalHierarchy::class, 'user_id', 'id')->where('type',3);
    }

    public function approved1()
    {
        return $this->hasOne(Evaluation::class, 'approved_by1', 'id');
    }  

    public function approved2()
    {
        return $this->hasOne(Evaluation::class, 'approved_by2', 'id');
    }  

    public function confirm1()
    {
        return $this->hasOne(Evaluation::class, 'confirmed_by1', 'id');
    }  

    public function confirm2()
    {
        return $this->hasOne(Evaluation::class, 'confirmed_by2', 'id');
    }  

    public function drafter2()
    {
        return $this->hasOne(Evaluation::class, 'draft_by2', 'id');
    }  


    public function activity()
    {
        return $this->hasOne(Activity::class, 'performed_by', 'id');
    } 




}
