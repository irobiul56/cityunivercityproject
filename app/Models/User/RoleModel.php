<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany(PermissionModel::class, 'role_permission');
    }

}
