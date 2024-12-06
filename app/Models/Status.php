<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';
    protected $primaryKey = 'status_id';
    // public $incrementing = false;
    // protected $keyType = 'string';
    protected $fillable = ['status_name'];

    public function projects()
    {
        return $this->hasMany(Projects::class, 'status', 'status_id');
    }
}
