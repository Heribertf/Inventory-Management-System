<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class InstallUninstallReport extends Model
{
    use HasFactory;

    protected $table = 'install_unistall_reports';
    protected $primaryKey = 'report_id';
    protected $fillable = [
        'customer',
        'model',
        'serial_number',
        'asset_code',
        'location',
        'date',
        'technician_name',
        'remarks',
        'report_type',
        'company',
        'delete_flag',
    ];

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-M-Y') : null;
    }
}
