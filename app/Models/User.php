<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'username',
        'phone',
        'password',
        'role',
        'type',
        'delete_flag',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'type' => 'integer',
        'role' => 'integer',
        'delete_flag' => 'boolean',
    ];

    protected $attributes = [
        'type' => 2,
        'delete_flag' => 0,
    ];

    public function getDepartmentsAttribute()
    {
        $userId = auth()->id();

        return DB::table('user_role_permissions')
            ->where('user_id', $userId)
            ->distinct()
            ->pluck('department')
            ->toArray();
    }
}
