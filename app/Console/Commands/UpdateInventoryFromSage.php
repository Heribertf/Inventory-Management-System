<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class UpdateInventoryFromSage extends Command
{
    const COMPANY_CODES = [
        'KEMDS' => 1,
        'KEDS' => 2,
        'EDMS' => 3,
        'KEBP' => 5,
        'KEPP' => 7,
    ];

    protected $signature = 'inventory:syncRecords';
    protected $description = 'Fetch new and updated machine records from Sage';

    public function handle()
    {
        $startTime = Carbon::now();
        $this->info('Syncing inventory table with Sage records...');
        $this->info('Process started at ' . $startTime->toDateTimeString());

        try {
            $this->updateMdsInventory();
            $this->logUpdateDetails($startTime);
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }

    private function updateMdsInventory()
    {
        $this->info('Fetching existing inventories from the local database...');
        $existingInventories = $this->fetchExistingInventories();
        $this->info('Fetched ' . count($existingInventories) . ' existing inventories.');

        $this->info('Fetching Sage records...');
        $sageRecords = $this->fetchSageRecords();

        $sageRecords->chunk(1000)->each(function ($chunk, $index) use ($existingInventories) {
            $this->info("Processing chunk " . ($index + 1) . " with " . $chunk->count() . " records.");

            $newRecords = $this->findNewRecords($chunk, $existingInventories);
            $updatedRecords = $this->findUpdatedRecords($chunk, $existingInventories);

            $this->info("Found " . $newRecords->count() . " new records.");
            $this->info("Found " . $updatedRecords->count() . " records to update.");

            $this->insertOrUpdateInventories($newRecords, true);
            $this->insertOrUpdateInventories($updatedRecords, false);

            $this->info("Chunk " . ($index + 1) . " processed.");
        });
    }

    private function fetchSageRecords(): LazyCollection
    {
        $this->info('Connecting to Sage database...');
        try {
            $records = DB::connection('sage_mds_connection')
                ->table('sage_mds_inventory_view')
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

            $this->info('Successfully connected to Sage database and fetched records.');
            return $records;
        } catch (\Exception $e) {
            $this->error('Error connecting to Sage database: ' . $e->getMessage());
            throw $e;
        }
    }



    private function fetchExistingInventories(): array
    {
        $this->info('Fetching existing inventories from the database...');
        return DB::table('inventory')
            ->where('delete_flag', 0)
            ->select([
                'serial_number',
                'collected_from',
                'item_group',
                'company',
                'model',
                'collection_date',
                'sage_ref',
                'sage_collection_date',
                DB::raw("CASE 
                        WHEN company = 1 THEN 'KEMDS' 
                        WHEN company = 2 THEN 'KEDS' 
                        WHEN company = 3 THEN 'EDMS'
                        WHEN company = 5 THEN 'KEBP'
                        WHEN company = 7 THEN 'KEPP'
                        ELSE 'KEMDS' 
                     END AS company_code")
            ])
            ->get()
            ->keyBy('serial_number')
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
    }

    private function findNewRecords(LazyCollection $sageRecords, array $existingInventories): LazyCollection
    {
        $this->info('Finding new records...');
        return $sageRecords->filter(function ($record) use ($existingInventories) {
            return !isset($existingInventories[$record->SerialNumber]);
        });
    }

    private function findUpdatedRecords(LazyCollection $sageRecords, array $existingInventories): LazyCollection
    {
        $this->info('Finding updated records...');
        return $sageRecords->filter(function ($record) use ($existingInventories) {
            if (isset($existingInventories[$record->SerialNumber])) {
                $existingRecord = $existingInventories[$record->SerialNumber];

                $fetchedSageDate = Carbon::parse($record->TXDate)->format('Y-m-d');
                $refSageDate = Carbon::parse($existingRecord['sage_collection_date'])->format('Y-m-d');

                $hasChanges = $existingRecord['collected_from'] !== $record->IBTDescription
                    || $refSageDate !== $fetchedSageDate || $existingRecord['company_code'] !== $record->ProjectCode;

                if ($hasChanges) {
                    $this->info("Record " . $record->SerialNumber . " has changed.");
                }

                return $hasChanges;
            }
            return false;
        });
    }

    private function insertOrUpdateInventories(LazyCollection $records, bool $update = true): void
    {
        $this->info('Inserting or updating records...');
        $records->each(function ($record) use ($update) {
            $company = self::COMPANY_CODES[$record->ProjectCode] ?? 1;

            $data = [
                'collected_from' => $record->IBTDescription,
                'company' => $company,
                'model' => $record->Model_Description,
                'serial_number' => $record->SerialNumber,
                'collection_date' => $record->TXDate,
                'sage_collection_date' => $record->TXDate,
                'item_group' => $record->ItemGroup,
            ];

            try {
                if ($update) {
                    DB::table('inventory')->updateOrInsert(
                        ['serial_number' => $record->SerialNumber],
                        [
                            'collected_from' => $record->IBTDescription,
                            'company' => $company,
                            'collection_date' => $record->TXDate,
                            'sage_collection_date' => $record->TXDate,
                            'item_group' => $record->ItemGroup,
                            'sage_date' => 1,
                            'filler_date' => 0,
                        ]
                    );
                } else {
                    DB::table('inventory')->updateOrInsert(
                        ['serial_number' => $record->SerialNumber],
                        $data
                    );
                }

                $this->info('Successfully updated/inserted record with serial number ' . $record->SerialNumber);
            } catch (\Exception $e) {
                $this->error('Error updating record with serial number ' . $record->SerialNumber . ': ' . $e->getMessage());
            }
        });
    }

    private function logUpdateDetails(Carbon $startTime): void
    {
        $endTime = Carbon::now();
        $this->info('Record sync completed on ' . $endTime->toDateTimeString());
        $this->info('Total time taken: ' . $endTime->diffInSeconds($startTime) . ' seconds.');
    }
}
