<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionDeliveryInventory extends Model
{
    use HasFactory;

    protected $table = 'collection_delivery_inventory';
    protected $primaryKey = 'inventory_id';

    protected $fillable = [
        'request_collection_date',
        'd_c_date',
        'client_name',
        'company',
        'asset_code',
        'model',
        'serial_number',
        'warehouse',
        'location',
        'branches',
        'status',
        'total_color',
        'total_b_w',
        'accessories',
        'ibt_number',
        'contact',
        'vehicle',
        'messenger',
        'ac_manager',
        'remarks',
        'comments',
        'dn_status',
        'files',
        'delete_flag',
    ];

    protected $casts = [
        'request_collection_date' => 'date',
        'd_c_date' => 'date',
        'delete_flag' => 'boolean',
    ];

    public function getRequestCollectionDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    public function getDCDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-M-Y') : null;
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
