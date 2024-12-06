<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    protected $fillable = [
        'role_name',
        'inventory',
    ];

    protected $casts = [
        'delete_flag' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role', 'role_id');
    }

    public function scopeActive($query)
    {
        return $query->where('delete_flag', 0);
    }

    // Helper method to check if a role has a specific inventory item
    public function hasInventoryItem($item)
    {
        return in_array($item, $this->inventory ?: []);
    }
}
