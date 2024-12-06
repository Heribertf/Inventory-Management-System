<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class UpdateInventoryFromSageMds extends BaseInventorySyncCommand
{
    protected $signature = 'inventory:sync_mds_records';
    protected $description = 'Fetch new and updated machine records from Sage MDS';

    protected function getConnection(): string
    {
        return 'sage_mds_connection';
    }

    protected function getViewName(): string
    {
        return 'sage_mds_inventory_view';
    }

    protected function fetchRecords(): LazyCollection
    {
        $this->info('Connecting to Sage MDS database...');
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

            $this->info('Successfully connected to Sage MDS database and fetched records.');
            return $records;
        } catch (\Exception $e) {
            $this->error('Error connecting to Sage MDS database: ' . $e->getMessage());
            throw $e;
        }
    }
}
