<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name','label'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user_website')
            ->withPivot('website_id')
            ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
            ->withTimestamps();
    }

    public function givePermissionTo($permission)
    {
        $permissionModel = is_string($permission) 
            ? Permission::where('name', $permission)->first() 
            : $permission;
        
        if ($permissionModel && !$this->permissions->contains($permissionModel->id)) {
            $this->permissions()->attach($permissionModel->id);
        }
    }

    public function revokePermissionTo($permission)
    {
        $permissionModel = is_string($permission) 
            ? Permission::where('name', $permission)->first() 
            : $permission;
        
        if ($permissionModel) {
            $this->permissions()->detach($permissionModel->id);
        }
    }

    public function hasPermission($permission)
    {
        $permissionName = is_string($permission) ? $permission : $permission->name;
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}
