<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    protected $fillable = [
        'collection_date',
        'collected_from',
        'company',
        'category',
        'model',
        'serial_number',
        'status',
        'dp_model',
        'dp_serial',
        'cb',
        'color',
        'mono_counter',
        'total',
        'fk',
        'dk',
        'dv',
        'belt',
        'feed',
        'dispatched_to',
        'dispatch_date',
        'warehouse',
        'dp_pf_out',
        'life_counter',
        'remarks',
        'files',
        'filler_date',
        'sage_date',
    ];

    protected $casts = [
        'collection_date' => 'date',
        'dispatch_date' => 'date',
        'color' => 'integer',
        'mono_counter' => 'integer',
        'total' => 'integer',
        'life_counter' => 'integer',
    ];

    protected $attributes = [
        'delete_flag' => 0,
        'filler_date' => 0,
        'sage_date' => 1,
    ];

    // Date Accessors
    public function getCollectionDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-M-Y') : null;
    }

    public function getSageCollectionDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-M-Y') : null;
    }

    public function getDispatchDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-M-Y') : null;
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
