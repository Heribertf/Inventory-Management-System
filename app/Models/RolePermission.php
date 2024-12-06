<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;
    protected $table = 'role_permissions';
    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'department',
        'permission'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
