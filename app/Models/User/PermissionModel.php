<?php

namespace App\Models\User;

use App\Models\User\RoleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Relationship: A permission can belong to many roles.
     */
    public function roles()
    {
        return $this->belongsToMany(RoleModel::class, 'role_permission');
    }
}
