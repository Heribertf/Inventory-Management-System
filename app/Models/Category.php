<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    // public $incrementing = false;
    // protected $keyType = 'string';
    protected $fillable = ['category_name'];

    public function projects()
    {
        return $this->hasMany(Projects::class, 'category', 'category_id');
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'category', 'category_id');
    }
}
