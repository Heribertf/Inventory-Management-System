<?php

namespace App\Http\Controllers;

use App\Models\CollectionDeliveryInventory;
use App\Models\Status;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CdRecordController extends Controller
{
    public function getRecordForm()
    {
        $user = Auth::user();
        $department = 'DC';

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $canWrite = in_array('write', $permissions);

        $backUrl = route('cd.home');
        if (!$canWrite) {
            return view('departments.403', compact('backUrl'));
        }

        $inventoryCompany = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->distinct()
            ->pluck('inventory')
            ->first();

        $companyArray = explode(',', $inventoryCompany);

        $companies = DB::table('companies')
            ->where('delete_flag', 0)
            ->orderBy('company_name', 'asc')
            ->get();
        $statuses = Status::where('delete_flag', 0)
            ->orderBy('status_name', 'asc')
            ->get();
        return view('cd-dept.add-asset', compact('companies', 'statuses', 'companyArray'));
    }

    public function submitRecord(Request $request)
    {
        $response = [
            'success' => false,
            'message' => 'Cannot complete request'
        ];

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'request-date' => 'nullable|date',
                'd-c-date' => 'nullable|date',
                'client-name' => 'nullable|string',
                'inventory-company' => 'nullable|integer',
                'asset-code' => 'nullable|string',
                'asset-model' => 'nullable|string',
                'serial-number' => 'nullable|string',
                'warehouse' => 'nullable|string',
                'location' => 'nullable|string',
                'branches' => 'nullable|string',
                'asset-status' => 'nullable|integer',
                'total-color' => 'nullable|integer',
                'total-bw' => 'nullable|integer',
                'accessories' => 'nullable|string',
                'ibt-number' => 'nullable|string',
                'contact' => 'nullable|string',
                'vehicle' => 'nullable|string',
                'messenger' => 'nullable|string',
                'ac-manager' => 'nullable|string',
                'dn-status' => 'nullable|string',
                'comments' => 'nullable|string',
                'remarks' => 'nullable|string',
                'asset-files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ], [
                'asset-files.*.mimes' => 'The file must be a JPG, JPEG, PNG, or PDF.',
                'asset-files.*.max' => 'The file should not exceed a size of 2MB.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ]);
            }

            $validator = $validator->validated();
            if (
                ((!empty($validator['asset-status']) && $validator['asset-status'] == 13) ||
                    (!empty($validator['asset-status']) && $validator['asset-status'] == 1)) &&
                !$request->hasFile('asset-files')
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload either a Collection Note or Delivery Note for Collected or Delivered assets respectively.'
                ]);
            }

            $uploadedFiles = [];
            if ($request->hasFile('asset-files')) {
                foreach ($request->file('asset-files') as $file) {
                    if ($file->isValid()) {
                        $serialNumber = $request->input('serial-number');
                        // $path = $file->store('uploads', 'public');
                        $fileName = $serialNumber . '_' . time() . $file->getClientOriginalExtension();
                        $path = $file->storeAs('uploads', $fileName, 'public');
                        $uploadedFiles[] = $path;
                    }
                }
            }

            $assetFiles = implode(',', $uploadedFiles);

            try {
                $record = new CollectionDeliveryInventory();

                $record->request_collection_date = $request->input('request-date');
                $record->d_c_date = $request->input('d-c-date');
                $record->client_name = $request->input('client-name');
                $record->company = $request->input('inventory-company');
                $record->asset_code = $request->input('asset-code');
                $record->model = $request->input('asset-model');
                $record->serial_number = $request->input('serial-number');
                $record->warehouse = $request->input('warehouse');
                $record->location = $request->input('location');
                $record->branches = $request->input('branches');
                $record->status = $request->input('asset-status', 'TO BE COLLECTED');
                $record->total_color = $request->input('total-color');
                $record->total_b_w = $request->input('total-bw');
                $record->accessories = $request->input('accessories');
                $record->ibt_number = $request->input('ibt-number');
                $record->contact = $request->input('contact');
                $record->vehicle = $request->input('vehicle');
                $record->messenger = $request->input('messenger');
                $record->ac_manager = $request->input('ac-manager');
                $record->remarks = $request->input('remarks');
                $record->comments = $request->input('comments');
                $record->dn_status = $request->input('dn-status');
                $record->files = $assetFiles;

                if ($record->save()) {
                    $response = [
                        'success' => true,
                        'message' => 'Record created successfully.'
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Unable to complete request.'
                    ];
                }
            } catch (\Exception $e) {
                $response = [
                    'success' => false,
                    'message' => 'An error occurred while submitting record.'
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request method'
            ];
        }

        return response()->json($response);
    }

    public function getRecords(Request $request)
    {
        $user = Auth::user();
        $department = 'DC';

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
            // return redirect('/cd-dept')->withErrors(['Cannot fetch data']);
            return response()->view('error', ['message' => 'Cannot fetch data'], 400);
        }

        $inventoryArray = explode(',', $inventories);

        // $companyMapping = [
        //     'MDS' => 1,
        //     'DS' => 2,
        //     'BANKING' => 5,
        //     'EDMS' => 3,
        //     'PROPRINT' => 7,
        //     'DS TI' => 6,
        //     'DS STB' => 10,
        //     'PPSL' => 8,
        //     'TS' => 9,
        // ];

        // $mappedArray = array_map(function ($value) use ($companyMapping) {
        //     return $companyMapping[$value] ?? null;
        // }, $inventoryArray);

        // dd($inventoryArray);

        $statusMapping = [
            'collected' => 1,
            'delivered' => 13,
            'to-be-collected' => 11,
            'untraced' => 12,
        ];

        $recordStatus = $statusMapping[$request->query('status')] ?? null;
        $title = array_search($recordStatus, $statusMapping);

        if (!$recordStatus) {
            $title = 'All';
        }

        $records = CollectionDeliveryInventory::with('status')
            ->select([
                'collection_delivery_inventory.*',
                'companies.company_name',
                'status.status_name'
            ])
            ->leftJoin('companies', 'collection_delivery_inventory.company', '=', 'companies.company_id')
            ->leftJoin('status', 'collection_delivery_inventory.status', '=', 'status.status_id')
            ->where('collection_delivery_inventory.status', $recordStatus)
            ->where('collection_delivery_inventory.delete_flag', 0)
            ->whereIn('collection_delivery_inventory.company', $inventoryArray)
            ->orderBy('collection_delivery_inventory.request_collection_date', 'desc')
            ->get();

        return view('cd-dept.records', [
            'records' => $records,
            'canWrite' => $canWrite,
            'title' => $title,
            'recordStatus' => $recordStatus,
        ]);
    }

    private $requestSourceMap = [
        1 => 'collected',
        11 => 'to-be-collected',
        12 => 'untraced',
        13 => 'delivered',
    ];
    public function fetchRecord(Request $request)
    {
        $user = Auth::user();
        $department = 'DC';

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $canWrite = in_array('write', $permissions);
        $backUrl = route('cd.home');
        if (!$canWrite) {
            return view('departments.403', compact('backUrl'));
        }

        $inventoryCompany = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->distinct()
            ->pluck('inventory')
            ->first();

        $companyArray = explode(',', $inventoryCompany);

        $request->validate([
            'inventory-id' => 'required|integer',
            'request-source' => 'required|string',
        ]);

        $recordId = $request->input('inventory-id');
        $requestSource = $request->input('request-source');

        $requestSource = $this->requestSourceMap[$requestSource] ?? 'default_source';

        $inventory = CollectionDeliveryInventory::find($recordId);

        if (!$inventory) {
            return $this->alertMessage("Record could not be found.", $requestSource);
        }
        $statuses = Status::select('status_id', 'status_name')
            ->where('delete_flag', 0)
            ->get();
        $companies = DB::table('companies')
            ->select('company_id', 'company_name')
            ->where('delete_flag', 0)
            ->get();

        return view('cd-dept.edit-record', compact('inventory', 'statuses', 'companyArray', 'companies', 'requestSource'));
    }

    public function updateRecord(Request $request)
    {
        $response = [
            'success' => false,
            'message' => 'Cannot complete request'
        ];

        if ($request->isMethod('post')) {
            $inventoryId = $request->input('inventory-id');
            $inventory = CollectionDeliveryInventory::find($inventoryId);

            if (!$inventory) {
                $response['message'] = 'Inventory item not found';
                return response()->json($response);
            }

            if (in_array($request->input('asset-status'), [13, 1]) && empty($inventory->files) && !$request->hasFile('asset-files')) {
                $response['message'] = 'Upload either a Collection Note or Delivery Note for Collected or Delivered assets respectively.';
                return response()->json($response);
            }

            $uploadedFiles = [];
            if ($request->hasFile('asset-files')) {
                foreach ($request->file('asset-files') as $file) {
                    if ($file->isValid()) {
                        $serialNumber = $request->input('serial-number');
                        $fileName = $serialNumber . '_' . time() . $file->getClientOriginalExtension();
                        $path = $file->storeAs('uploads', $fileName, 'public');
                        $uploadedFiles[] = $path;
                    }
                }
                $assetFiles = $inventory->files ? $inventory->files . ',' . implode(',', $uploadedFiles) : implode(',', $uploadedFiles);
            } else {
                $assetFiles = $inventory->files;
            }

            $inventory->update([
                'request_collection_date' => $request->input('request-date'),
                'd_c_date' => $request->input('d-c-date'),
                'client_name' => $request->input('client-name'),
                'company' => $request->input('inventory-company'),
                'asset_code' => $request->input('asset-code'),
                'model' => $request->input('asset-model'),
                'serial_number' => $request->input('serial-number'),
                'warehouse' => $request->input('warehouse'),
                'location' => $request->input('location'),
                'branches' => $request->input('branches'),
                'status' => $request->input('asset-status', 'TO BE COLLECTED'),
                'total_color' => $request->input('total-color'),
                'total_b_w' => $request->input('total-bw'),
                'accessories' => $request->input('accessories'),
                'ibt_number' => $request->input('ibt-number'),
                'contact' => $request->input('contact'),
                'vehicle' => $request->input('vehicle'),
                'messenger' => $request->input('messenger'),
                'ac_manager' => $request->input('ac-manager'),
                'remarks' => $request->input('remarks'),
                'dn_status' => $request->input('dn-status'),
                'files' => $assetFiles
            ]);

            $response = [
                'success' => true,
                'message' => 'Record updated successfully.'
            ];
        } else {
            $response['message'] = 'Invalid request method';
        }

        return response()->json($response);
    }

    private function alertMessage($message, $url)
    {
        return response()->json(['success' => false, 'message' => $message], 400);
    }
}
