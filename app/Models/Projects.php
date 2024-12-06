<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'project_id';
    protected $fillable = [
        'inventory_id',
        'request_date',
        'client',
        'category',
        'model',
        'serial_number',
        'total_counter',
        'ac_manager',
        'priority',
        'tech_name',
        'deadline',
        'days_left',
        'state',
        'status_page',
        'status',
        'delete_flag',
    ];

    protected $casts = [
        'request_date' => 'date',
    ];

    protected $attributes = [
        'delete_flag' => 0,
    ];

    // Date Accessors
    public function getRequestDateAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }

        return Carbon::parse($value)->format('d-M-Y');
    }

    public function getDeadlineAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }

        return Carbon::parse($value)->format('d-M-Y');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    
}
