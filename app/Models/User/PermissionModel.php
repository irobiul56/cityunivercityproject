<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(RoleModel::class, 'role_permission');
    }


}
