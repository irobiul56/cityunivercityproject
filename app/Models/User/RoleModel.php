<?php

namespace App\Models\User;

use App\Models\User;
use App\Models\User\PermissionModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Relationship: A role can have many permissions.
     */
    public function permissions()
    {
        return $this->belongsToMany(PermissionModel::class, 'role_permission');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
    
}
