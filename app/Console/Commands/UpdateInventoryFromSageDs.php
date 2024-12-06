<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class UpdateInventoryFromSageDs extends BaseInventorySyncCommand
{
    protected $signature = 'inventory:sync_ds_records';
    protected $description = 'Fetch new and updated machine records from Sage DS';

    protected function getConnection(): string
    {
        return 'sage_ds_connection';
    }

    protected function getViewName(): string
    {
        return 'sage_ds_inventory_view';
    }

    protected function fetchRecords(): LazyCollection
    {
        $this->info('Connecting to Sage DS database...');
        try {
            $records = DB::connection($this->getConnection())
                ->table($this->getViewName())
                ->select([
                    'IBTNumber',
                    'Model_Description',
                    'ItemGroup',
                    'IBTDescription',
                    'ProjectCode',
                    'SerialNumber',
                    'TXDate'
                ])
                ->orderBy('TXDate', 'desc')
                ->cursor();

            $this->info('Successfully connected to Sage DS database and fetched records.');
            return $records;
        } catch (\Exception $e) {
            $this->error('Error connecting to Sage DS database: ' . $e->getMessage());
            throw $e;
        }
    }
}
