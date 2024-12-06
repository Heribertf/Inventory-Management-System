<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Status;
use App\Models\Inventory;

use Carbon\Carbon;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Http\JsonResponse;

use PhpOffice\PhpSpreadsheet\IOFactory;

class InventoryController extends Controller
{
    public function getInventoryStats(Request $request)
    {
        $currentUser = Auth::id();
        $department = 'FR';

        $inventories = DB::table('user_role_permissions')
            ->where('user_id', $currentUser)
            ->where('department', $department)
            ->distinct()
            ->pluck('inventory')
            ->toArray();

        // // if ($inventories->isEmpty()) {
        // //     return response()->json([], 200);
        // // }
        $inventories = $inventories[0];
        $inventories = explode(',', $inventories);
        // var_dump($inventories);

        $selectedYear = $request->input('year', date('Y'));

        $collectedQuery = DB::table('inventory')
            ->selectRaw('MONTH(collection_date) as month, COUNT(*) as collected_count')
            ->whereNotNull('collection_date')
            ->whereYear('collection_date', $selectedYear)
            ->whereIn('company', $inventories)
            ->groupBy(DB::raw('MONTH(collection_date)'));

        $dispatchedQuery = DB::table('inventory')
            ->selectRaw('MONTH(dispatch_date) as month, COUNT(*) as dispatched_count')
            ->where('status', 2)
            ->whereYear('dispatch_date', $selectedYear)
            ->whereIn('company', $inventories)
            ->groupBy(DB::raw('MONTH(dispatch_date)'));

        $leftJoin = DB::query()
            ->select(
                DB::raw('collected.month'),
                DB::raw('collected.collected_count'),
                DB::raw('COALESCE(dispatched.dispatched_count, 0) AS dispatched_count')
            )
            ->fromSub($collectedQuery, 'collected')
            ->leftJoinSub($dispatchedQuery, 'dispatched', 'collected.month', '=', 'dispatched.month');

        $rightJoin = DB::query()
            ->select(
                DB::raw('dispatched.month'),
                DB::raw('COALESCE(collected.collected_count, 0) AS collected_count'),
                DB::raw('dispatched.dispatched_count')
            )
            ->fromSub($dispatchedQuery, 'dispatched')
            ->leftJoinSub($collectedQuery, 'collected', 'dispatched.month', '=', 'collected.month')
            ->whereNull('collected.month');

        $retrievedData = $leftJoin->union($rightJoin)->orderBy('month')->get();

        $data = $retrievedData->map(function ($row) {
            return [
                "x" => (int) $row->month,
                "y" => (int) $row->dispatched_count,
                "z" => (int) $row->collected_count
            ];
        });

        return response()->json($data);
    }
    public function getYears(): JsonResponse
    {
        $years = DB::table(DB::raw("(SELECT collection_date AS date FROM inventory UNION SELECT dispatch_date AS date FROM inventory WHERE status = 2) AS combined_dates"))
            ->selectRaw('DISTINCT YEAR(date) AS year')
            ->whereNotNull('date')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json($years);
    }

    public function getInventoryForm()
    {
        $user = Auth::user();
        $department = "FR";

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $backUrl = route('fr.home');
        if (!in_array('write', $permissions)) {
            return view('departments.403', compact('backUrl'));
        }

        $categories = Category::where('delete_flag', 0)->get();
        $statuses = Status::where('delete_flag', 0)
            ->orderBy('status_name', 'asc')
            ->get();
        $companies = DB::table('companies')
            ->select('company_id', 'company_name')
            ->where('delete_flag', 0)
            ->get();

        return view('fr-dept.add-inventory', compact('categories', 'statuses', 'companies'));
    }


    // public function submitInventory(Request $request)
    // {
    //     $user = Auth::user();
    //     $department = "FR";

    //     $permissions = DB::table('user_role_permissions')
    //         ->where('user_id', $user->user_id)
    //         ->where('department', $department)
    //         ->pluck('permission')
    //         ->toArray();

    //     if (!in_array('write', $permissions)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unable to process your request'
    //         ]);
    //     }

    //     $validatedData = Validator::make($request->all(), [
    //         'collection-date' => 'nullable|date',
    //         'collected-from' => 'nullable|string|max:255',
    //         'inventory-company' => 'nullable|integer',
    //         'inventory-category' => 'nullable|integer',
    //         'inventory-model' => 'nullable|string|max:255',
    //         'serial-number' => 'nullable|string|max:255',
    //         'inventory-status' => 'nullable|integer',
    //         'dp-model' => 'nullable|string|max:255',
    //         'dp-serial' => 'nullable|string|max:255',
    //         'inventory-cb' => 'nullable|string|max:255',
    //         'inventory-color' => 'nullable|integer',
    //         'mono-counter' => 'nullable|integer',
    //         'inventory-total' => 'nullable|integer',
    //         'fk' => 'nullable|string|max:255',
    //         'dk' => 'nullable|string|max:255',
    //         'dv' => 'nullable|string|max:255',
    //         'belt' => 'nullable|string|max:255',
    //         'feed' => 'nullable|string|max:255',
    //         'dispatched-to' => 'nullable|string|max:255',
    //         'dispatch-date' => 'nullable|date',
    //         'warehouse' => 'nullable|string|max:255',
    //         'dp-pf-out' => 'nullable|string|max:255',
    //         'life-counter' => 'nullable|integer',
    //         'remarks' => 'nullable|string|max:300',
    //         'inventory-files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
    //     ], [
    //         'inventory-files.*.mimes' => 'The file must be a JPG, JPEG, PNG, or PDF.',
    //         'inventory-files.*.max' => 'The file should not exceed a size of 2MB.'
    //     ]);

    //     if ($validatedData->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validatedData->errors()->first(),
    //         ]);
    //     }

    //     $validatedData = $validatedData->validated();

    //     // if (((!empty($validatedData['inventory-status']) && $validatedData['inventory-status'] == 2) || (!empty($validatedData['inventory-status']) && $validatedData['inventory-status'] == 1)) && empty($request->file('inventory-files'))) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'Files are required when the status is DISPATCHED or COLLECTED.'
    //     //     ]);
    //     // }

    //     if (
    //         ((!empty($validatedData['inventory-status']) && $validatedData['inventory-status'] == 2) ||
    //             (!empty($validatedData['inventory-status']) && $validatedData['inventory-status'] == 1)) &&
    //         !$request->hasFile('inventory-files')
    //     ) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Files are required when the status is DISPATCHED or COLLECTED.'
    //         ]);
    //     }

    //     $uploadedFiles = [];
    //     if ($request->hasFile('inventory-files')) {
    //         foreach ($request->file('inventory-files') as $file) {
    //             if ($file->isValid()) {
    //                 $path = $file->store('uploads', 'public');
    //                 $uploadedFiles[] = $path;
    //             }
    //         }
    //     }

    //     $inventory = new Inventory();
    //     $inventory->collection_date = $validatedData['collection-date'] ?? null;
    //     $inventory->collected_from = $validatedData['collected-from'] ?? null;
    //     $inventory->company = $validatedData['inventory-company'] ?? null;
    //     $inventory->category = $validatedData['inventory-category'] ?? null;
    //     $inventory->model = $validatedData['inventory-model'] ?? null;
    //     $inventory->serial_number = $validatedData['serial-number'] ?? null;
    //     $inventory->status = $validatedData['inventory-status'] ?? null;
    //     $inventory->dp_model = $validatedData['dp-model'] ?? null;
    //     $inventory->dp_serial = $validatedData['dp-serial'] ?? null;
    //     $inventory->cb = $validatedData['inventory-cb'] ?? null;
    //     $inventory->color = $validatedData['inventory-color'] ?? null;
    //     $inventory->mono_counter = $validatedData['mono-counter'] ?? null;
    //     $inventory->total = $validatedData['inventory-total'] ?? null;
    //     $inventory->fk = $validatedData['fk'] ?? null;
    //     $inventory->dk = $validatedData['dk'] ?? null;
    //     $inventory->dv = $validatedData['dv'] ?? null;
    //     $inventory->belt = $validatedData['belt'] ?? null;
    //     $inventory->feed = $validatedData['feed'] ?? null;
    //     $inventory->dispatched_to = $validatedData['dispatched-to'] ?? null;
    //     $inventory->dispatch_date = $validatedData['dispatch-date'] ?? null;
    //     $inventory->warehouse = $validatedData['warehouse'] ?? null;
    //     $inventory->dp_pf_out = $validatedData['dp-pf-out'] ?? null;
    //     $inventory->life_counter = $validatedData['life-counter'] ?? null;
    //     $inventory->remarks = $validatedData['remarks'] ?? null;
    //     $inventory->files = implode(',', $uploadedFiles);

    //     if ($inventory->save()) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Inventory created successfully.'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unable to complete request.'
    //         ]);
    //     }
    // }

    public function submitExcel(Request $request)
    {
        $response = ['success' => false, 'message' => ''];

        if ($request->hasFile('inventory-file') && $request->file('inventory-file')->isValid()) {
            $file = $request->file('inventory-file');
            $mimeType = $file->getMimeType();
            $allowedMimeTypes = [
                'text/x-comma-separated-values',
                'text/comma-separated-values',
                'application/octet-stream',
                'application/vnd.ms-excel',
                'application/x-csv',
                'text/x-csv',
                'text/csv',
                'application/csv',
                'application/excel',
                'application/vnd.msexcel',
                'text/plain',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];

            if (in_array($mimeType, $allowedMimeTypes)) {
                $extension = $file->getClientOriginalExtension();
                $reader = ($extension === 'csv') ? IOFactory::createReader('Csv') : IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load($file->getRealPath());
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                array_shift($sheetData);

                // dd($sheetData);
                DB::beginTransaction();
                try {
                    foreach ($sheetData as $rowData) {
                        $collectionDate = $this->formatDate($rowData[0]);
                        $collectedFrom = $rowData[1] ?? null;
                        $company = $rowData[2] ?? null;
                        $categoryName = $rowData[3] ?? null;
                        $category = $categoryName ? $this->getCategoryId($categoryName) : null;
                        $model = $rowData[4] ?? null;
                        $serialNumber = $rowData[5] ?? null;
                        $statusName = $rowData[6] ?? null;
                        $status = $statusName ? $this->getStatusId($statusName) : null;
                        $dpModel = $rowData[7] ?? null;
                        $dpSerial = $rowData[8] ?? null;
                        $cb = $rowData[9] ?? null;
                        $color = $this->sanitizeNumeric($rowData[10] ?? null);
                        $monoCounter = $this->sanitizeNumeric($rowData[11] ?? null);
                        $total = $this->sanitizeNumeric($rowData[12] ?? null);
                        $fk = $rowData[13] ?? null;
                        $dk = $rowData[14] ?? null;
                        $dv = $rowData[15] ?? null;
                        $belt = $rowData[16] ?? null;
                        $feed = $rowData[17] ?? null;
                        $dispatchedTo = $rowData[18] ?? null;
                        $dispatchDate = $this->formatDate($rowData[19]);
                        $warehouse = $rowData[20] ?? null;
                        $dpPfOut = $rowData[21] ?? null;
                        $lifeCounter = $this->sanitizeNumeric($rowData[22] ?? null);
                        $remarks = $rowData[23] ?? null;

                        if ($serialNumber) {
                            $existingRecord = Inventory::where('serial_number', $serialNumber)->first();
                            if ($existingRecord) {
                                Inventory::where('serial_number', $serialNumber)->update([
                                    'collection_date' => $collectionDate,
                                    'category' => $category,
                                    'model' => $model,
                                    'status' => $status,
                                    'dp_model' => $dpModel,
                                    'dp_serial' => $dpSerial,
                                    'cb' => $cb,
                                    'color' => $color,
                                    'mono_counter' => $monoCounter,
                                    'total' => $total,
                                    'fk' => $fk,
                                    'dk' => $dk,
                                    'dv' => $dv,
                                    'belt' => $belt,
                                    'feed' => $feed,
                                    'dispatched_to' => $dispatchedTo,
                                    'dispatch_date' => $dispatchDate,
                                    'warehouse' => $warehouse,
                                    'dp_pf_out' => $dpPfOut,
                                    'life_counter' => $lifeCounter,
                                    'remarks' => $remarks,
                                ]);
                            }
                        }

                        // DB::table('inventory')->insert([
                        //     'collection_date' => $collectionDate,
                        //     'collected_from' => $collectedFrom,
                        //     'company' => $company,
                        //     'category' => $category,
                        //     'model' => $model,
                        //     'serial_number' => $serialNumber,
                        //     'status' => $status,
                        //     'dp_model' => $dpModel,
                        //     'dp_serial' => $dpSerial,
                        //     'cb' => $cb,
                        //     'color' => $color,
                        //     'mono_counter' => $monoCounter,
                        //     'total' => $total,
                        //     'fk' => $fk,
                        //     'dk' => $dk,
                        //     'dv' => $dv,
                        //     'belt' => $belt,
                        //     'feed' => $feed,
                        //     'dispatched_to' => $dispatchedTo,
                        //     'dispatch_date' => $dispatchDate,
                        //     'warehouse' => $warehouse,
                        //     'dp_pf_out' => $dpPfOut,
                        //     'life_counter' => $lifeCounter,
                        //     'remarks' => $remarks,
                        //     'files' => $file->getClientOriginalName(),
                        // ]);
                    }

                    DB::commit();
                    $response['success'] = true;
                    $response['message'] = 'Inventories successfully imported and updated.';
                } catch (Exception $e) {
                    DB::rollBack();
                    $response['message'] = 'There was an error importing inventory data';
                }
            } else {
                $response['message'] = 'Upload only CSV or Excel file.';
            }
        } else {
            $response['message'] = 'No file uploaded or upload error.';
        }

        return response()->json($response);
    }

    private function formatDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        $date = \DateTime::createFromFormat('d-M-y', $dateString);
        return $date ? $date->format('Y-m-d') : null;
    }

    private function getCategoryId($categoryName)
    {
        $categoryName = strtoupper($categoryName);
        $category = DB::table('categories')->where('category_name', $categoryName)->value('category_id');
        return $category;
    }

    private function getStatusId($statusName)
    {
        $statusName = strtoupper($statusName);
        $status = DB::table('status')->where('status_name', $statusName)->value('status_id');
        return $status;
    }

    private function sanitizeNumeric($value)
    {
        return preg_replace('/[^0-9.-]/', '', $value);
    }

    public function updateInventory(Request $request)
    {
        $user = Auth::user();
        $department = "FR";

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        if (!in_array('write', $permissions)) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to process your request'
            ]);
        }

        $validatedData = $request->validate([
            'inventory-id' => 'required|integer|exists:inventory,inventory_id',
            'collection-date' => 'nullable|date',
            'collected-from' => 'nullable|string|max:255',
            'inventory-company' => 'nullable|integer',
            'inventory-category' => 'nullable|integer',
            'inventory-model' => 'nullable|string|max:255',
            'serial-number' => 'nullable|string|max:255',
            'inventory-status' => 'nullable|integer',
            'dp-model' => 'nullable|string|max:255',
            'dp-serial' => 'nullable|string|max:255',
            'inventory-cb' => 'nullable|string|max:255',
            'inventory-color' => 'nullable|integer',
            'mono-counter' => 'nullable|integer',
            'inventory-total' => 'nullable|integer',
            'fk' => 'nullable|string|max:255',
            'dk' => 'nullable|string|max:255',
            'dv' => 'nullable|string|max:255',
            'belt' => 'nullable|string|max:255',
            'feed' => 'nullable|string|max:255',
            'dispatched-to' => 'nullable|string|max:255',
            'dispatch-date' => 'nullable|date',
            'warehouse' => 'nullable|string|max:255',
            'dp-pf-out' => 'nullable|string|max:255',
            'life-counter' => 'nullable|integer',
            'remarks' => 'nullable|string|max:300',
            'inventory-files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        if (((!empty($validatedData['inventory-status']) && $validatedData['inventory-status'] == 2) || (!empty($validatedData['inventory-status']) && $validatedData['inventory-status'] == 1)) && empty($request->file('inventory-files'))) {
            return response()->json([
                'success' => false,
                'message' => 'Files are required when the status is DISPATCHED or COLLECTED.'
            ]);
        }

        $uploadedFiles = [];
        if ($request->hasFile('inventory-files')) {
            foreach ($request->file('inventory-files') as $file) {
                $path = $file->store('uploads', 'public');
                $uploadedFiles[] = $path;
            }
        }

        $inventory = Inventory::find($validatedData['inventory-id']);
        if (!$inventory) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory record not found.'
            ]);
        }

        $machineStatus = $validatedData['inventory-status'];
        if ($machineStatus == 1) {
            $history = [
                'inventory_id' => $validatedData['inventory-id'],
                'collection_date' => $validatedData['collection-date'],
                'collected_from' => $validatedData['collected-from'],
                'total_counter' => $validatedData['inventory-total'],
                'dp_serial' => $validatedData['dp-serial'],
                'dp_pf_out' => $validatedData['dp-pf-out'],
                'toner_k' => $request->input('toner-k'),
                'toner_y' => $request->input('toner-y'),
                'toner_m' => $request->input('toner-m'),
                'toner_c' => $request->input('toner-c'),
                'remarks' => $validatedData['remarks'],
                'status' => 1,
            ];

            DB::table('machine_history')->insert($history);
        } elseif ($machineStatus == 2) {
            $history = [
                'inventory_id' => $validatedData['inventory-id'],
                'dispatch_date' => $validatedData['dispatch-date'],
                'dispatched_to' => $validatedData['dispatched-to'],
                'total_counter' => $validatedData['inventory-total'],
                'dp_serial' => $validatedData['dp-serial'],
                'dp_pf_out' => $validatedData['dp-pf-out'],
                'toner_k' => $request->input('toner-k'),
                'toner_y' => $request->input('toner-y'),
                'toner_m' => $request->input('toner-m'),
                'toner_c' => $request->input('toner-c'),
                'remarks' => $validatedData['remarks'],
                'status' => 2,
            ];

            DB::table('machine_history')->insert($history);
        } elseif ($machineStatus == 3) {
            $history = [
                'inventory_id' => $validatedData['inventory-id'],
                'total_counter' => $validatedData['inventory-total'],
                'collection_date' => $validatedData['collection-date'],
                'collected_from' => $validatedData['collected-from'],
                'dp_serial' => $validatedData['dp-serial'],
                'dp_pf_out' => $validatedData['dp-pf-out'],
                'toner_k' => $request->input('toner-k'),
                'toner_y' => $request->input('toner-y'),
                'toner_m' => $request->input('toner-m'),
                'toner_c' => $request->input('toner-c'),
                'remarks' => $validatedData['remarks'],
                'status' => 3,
            ];

            DB::table('machine_history')->insert($history);
        }


        if ($validatedData['collection-date'] != $inventory->sage_collection_date) {
            $inventory->collection_date = $validatedData['collection-date'] ?? $inventory->collection_date;
            $inventory->filler_date = 1;
            $inventory->sage_date = 0;
        }

        // $inventory->collected_from = $validatedData['collected-from'] ?? $inventory->collected_from;
        $inventory->company = $validatedData['inventory-company'] ?? $inventory->company;
        $inventory->category = $validatedData['inventory-category'] ?? $inventory->category;
        // $inventory->model = $validatedData['inventory-model'] ?? $inventory->model;
        // $inventory->serial_number = $validatedData['serial-number'] ?? $inventory->serial_number;
        $inventory->status = $validatedData['inventory-status'] ?? $inventory->status;
        $inventory->dp_model = $validatedData['dp-model'] ?? $inventory->dp_model;
        $inventory->dp_serial = $validatedData['dp-serial'] ?? $inventory->dp_serial;
        $inventory->cb = $validatedData['inventory-cb'] ?? $inventory->cb;
        $inventory->color = $validatedData['inventory-color'] ?? $inventory->color;
        $inventory->mono_counter = $validatedData['mono-counter'] ?? $inventory->mono_counter;
        $inventory->total = $validatedData['inventory-total'] ?? $inventory->total;
        $inventory->fk = $validatedData['fk'] ?? $inventory->fk;
        $inventory->dk = $validatedData['dk'] ?? $inventory->dk;
        $inventory->dv = $validatedData['dv'] ?? $inventory->dv;
        $inventory->belt = $validatedData['belt'] ?? $inventory->belt;
        $inventory->feed = $validatedData['feed'] ?? $inventory->feed;
        $inventory->dispatched_to = $validatedData['dispatched-to'] ?? $inventory->dispatched_to;
        $inventory->dispatch_date = $validatedData['dispatch-date'] ?? $inventory->dispatch_date;
        $inventory->warehouse = $validatedData['warehouse'] ?? $inventory->warehouse;
        $inventory->dp_pf_out = $validatedData['dp-pf-out'] ?? $inventory->dp_pf_out;
        $inventory->life_counter = $validatedData['life-counter'] ?? $inventory->life_counter;
        $inventory->remarks = $validatedData['remarks'] ?? $inventory->remarks;
        $inventory->files = !empty($uploadedFiles) ? implode(',', $uploadedFiles) : $inventory->files;

        if ($inventory->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Inventory updated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to complete request.'
            ]);
        }
    }

    public function fetchInventories(Request $request)
    {
        $user = Auth::user();
        $department = 'FR';

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $inventoryCompanies = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->distinct()
            ->pluck('inventory')
            ->toArray();

        if (empty($inventoryCompanies)) {
            return redirect('/fr-dept')->with('error', 'Cannot fetch data');
        }
        if (count($inventoryCompanies) > 0) {
            $inventoryCompanies = explode(',', $inventoryCompanies[0]);
        }

        $inventories = Inventory::with('category', 'status')
            ->select([
                'inventory.*',
                'categories.category_name',
                'status.status_name',
                'companies.company_name',
            ])
            ->leftJoin('categories', 'inventory.category', '=', 'categories.category_id')
            ->leftJoin('status', 'inventory.status', '=', 'status.status_id')
            ->leftJoin('companies', 'inventory.company', '=', 'companies.company_id')
            ->whereIn('inventory.company', $inventoryCompanies)
            ->where('inventory.delete_flag', 0)
            ->orderBy('inventory.collection_date', 'desc')
            ->get()
            ->map(function ($inventory) {
                $inventory->collectionDate = $inventory->collection_date;
                $inventory->sageCollectionDate = $inventory->sage_collection_date;
                $inventory->dispatchDate = $inventory->dispatch_date;
                return $inventory;
            });

        $sage_records = DB::connection('sage_mds_connection')
            ->table('_etblWhseIBT as e')
            ->join('client as c', 'c.Account', '=', 'e.ucIBTCustomerCode')
            ->leftJoin('SerialTX as st', 'st.cSNTXReference2', '=', 'e.cIBTNumber')
            ->leftJoin('SerialMF as sn', 'sn.SerialCounter', '=', 'st.SNLink')
            ->leftJoin('StkItem as i', 'i.StockLink', '=', 'sn.SNStockLink')
            ->select([
                'e.cIBTNumber as IBTNumber',
                'e.cIBTDescription as IBTDescription',
                'e.ucIBTCustomerCode as Account',
                'c.Name',
                'sn.SerialNumber',
                'i.Code',
                'st.SNTxReference as Reference',
                'st.SNTxDate as TXDate'
            ])
            ->whereIn('e.iWhseIDTo', [26, 66, 67, 69, 76, 80, 81, 84, 85])
            ->whereNotNull('sn.SerialNumber')
            ->distinct()
            ->orderBy('st.SNTxDate', 'desc')
            ->get()
            ->map(function ($inventory) {
                $inventory->TXDate = Carbon::parse($inventory->TXDate)->format('d-M-Y');
                return $inventory;
            });

        $combinedInventories = $sage_records->merge($inventories);

        return view('fr-dept.inventories', [
            'inventories' => $inventories,
            'canWrite' => in_array('write', $permissions),
        ]);
    }

    public function fetchInventoryDetail(Request $request)
    {
        $user = Auth::user();
        $department = "FR";

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $backUrl = route('fr.home');
        if (!in_array('write', $permissions)) {
            return view('departments.403', compact('backUrl'));
        }

        $inventoryId = $request->input('inventory-id');
        $inventory = Inventory::find($inventoryId);

        $companies = DB::table('companies')
            ->where('delete_flag', 0)
            ->orderBy('company_name', 'asc')
            ->get();
        $categories = Category::select('category_id', 'category_name')
            ->where('delete_flag', 0)
            ->get();
        $statuses = Status::select('status_id', 'status_name')
            ->where('delete_flag', 0)
            ->orderBy('status_name', 'asc')
            ->get();

        return view('fr-dept.edit-inventory', compact('inventory', 'companies', 'categories', 'statuses'));
    }

    public function deleteInventory(Request $request)
    {
        $user = Auth::user();
        $department = "FR";

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        if (!in_array('write', $permissions)) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to process your request'
            ]);
        }

        $validatedData = $request->validate([
            'inventoryId' => 'required|integer|exists:inventory,inventory_id',
        ]);

        $inventoryId = $validatedData['inventoryId'];

        // $inventoryId = $request->input('inventory-id');
        $inventory = Inventory::find($inventoryId);

        if (!$inventory) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory could not be found.'
            ]);
        } else {
            // $inventory->delete_flag = 1;
            if ($inventory->delete()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventory deleted successfully.'
                ]);
            }
        }
    }

    public function getInventoryReport(Request $request)
    {
        $user = Auth::user();
        $department = 'FR';

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $canWrite = in_array('write', $permissions);

        $inventories = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->distinct()
            ->pluck('inventory')
            ->first();

        if (!$inventories) {
            return response()->view('error', ['message' => 'Cannot fetch data'], 400);
        }

        $inventoryArray = explode(',', $inventories);

        // if (empty($inventoryCompanies)) {
        //     return redirect('/fr-dept')->with('error', 'Cannot fetch data');
        // }
        // if (count($inventoryCompanies) > 0) {
        //     $inventoryCompanies = explode(',', $inventoryCompanies[0]);
        // }

        $statusMapping = [
            'dispatched' => 2,
            'collected' => 1,
            'disposed' => 3,
            'ready' => 4,
            'serviceable' => 5,
            'spare' => 6,
            'dispose' => 7,
        ];

        $inventoryStatus = $statusMapping[$request->query('report')] ?? null;
        $title = array_search($inventoryStatus, $statusMapping);

        if (!$inventoryStatus) {
            $title = 'All';
        }

        $inventoryReport = Inventory::with('category', 'status')
            ->select([
                'inventory.*',
                'categories.category_name',
                'status.status_name'
            ])
            ->leftJoin('categories', 'inventory.category', '=', 'categories.category_id')
            ->leftJoin('status', 'inventory.status', '=', 'status.status_id')
            ->whereIn('inventory.company', $inventoryArray)
            ->where('inventory.status', $inventoryStatus)
            ->where('inventory.delete_flag', 0)
            ->orderBy('inventory.collection_date', 'desc')
            ->get()
            ->map(function ($inventory) {
                $inventory->collectionDate = $inventory->collection_date;
                $inventory->dispatchDate = $inventory->dispatch_date ? $inventory->dispatch_date : null;
                return $inventory;
            });

        return view('fr-dept.inventory-report', compact('inventoryReport', 'title', 'canWrite', 'inventoryStatus'));
    }

    public function searchSerial(Request $request)
    {
        $term = $request->query('term', '');
        $term = $term . '%';

        $inventories = Inventory::where('serial_number', 'like', $term)
            ->select('collected_from', 'serial_number', 'model', 'status', 'total', 'category')
            ->get();

        $serialNumbers = $inventories->map(function ($inventory) {
            return [
                'value' => $inventory->serial_number,
                'client' => $inventory->collected_from,
                'category_id' => $inventory->category,
                'model' => $inventory->model,
                'status' => $inventory->status,
                'total_counter' => $inventory->total,
            ];
        });

        return response()->json($serialNumbers);
    }

    public function getHistory(Request $request)
    {
        $serialNumber = $request->input('inventory_serial');

        $machineHistory = DB::table('machine_history')
            ->select([
                'machine_history.*',
                'inventory.model',
                'inventory.serial_number',
            ])
            ->join('inventory', 'machine_history.inventory_id', '=', 'inventory.inventory_id')
            ->where('inventory.serial_number', $serialNumber)
            ->get();

        if ($machineHistory->isEmpty()) {
            return response()->json(['message' => 'The requested serial number has no history records'], 404);
        }

        // var_dump(response()->json($machineHistory));

        // dd($machineHistory->toArray());
        return response()->json($machineHistory);
    }
}
