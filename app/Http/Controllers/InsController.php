<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\InstallUninstallReport;

class InsController extends Controller
{
    public function getReportForm()
    {
        $user = Auth::user();
        $department = 'INS';

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $canWrite = in_array('write', $permissions);

        $inventoryCompany = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->distinct()
            ->pluck('inventory')
            ->first();

        $companyArray = explode(',', $inventoryCompany);

        $backUrl = route('ins.home');
        if (!$canWrite) {
            return view('departments.403', compact('backUrl'));
        }

        $companies = DB::table('companies')
            ->where('delete_flag', 0)
            ->orderBy('company_name', 'asc')
            ->get();
        return view('ins-dept.add-report', compact('companies', 'companyArray'));
    }

    public function submitReport(Request $request)
    {
        $response = [
            'success' => false,
            'message' => 'Cannot complete request'
        ];

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'report-type' => 'required|string',
                'customer' => 'nullable|string',
                'model' => 'nullable|string',
                'serial-number' => 'nullable|string',
                'asset-code' => 'nullable|string',
                'location' => 'nullable|string',
                'date' => 'nullable|date',
                'tech-name' => 'nullable|string',
                'report-company' => 'nullable|integer',
                'remarks' => 'nullable|string'
            ], [
                'report-type.required' => 'Please select the type of report.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ]);
            }

            $report = InstallUninstallReport::create([
                'customer' => $request->input('customer') ?? null,
                'model' => $request->input('model') ?? null,
                'serial_number' => $request->input('serial-number') ?? null,
                'asset_code' => $request->input('asset-code') ?? null,
                'location' => $request->input('location') ?? null,
                'date' => $request->input('date') ?? null,
                'technician_name' => $request->input('tech-name') ?? null,
                'remarks' => $request->input('remarks') ?? null,
                'report_type' => $request->input('report-type'),
                'company' => $request->input('report-company') ?? null,
            ]);

            if ($report) {
                $response = [
                    'success' => true,
                    'message' => 'Report submitted successfully.'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Unable to complete request.'
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

    public function getReport(Request $request)
    {
        $user = Auth::user();
        $department = 'INS';

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $canWrite = in_array('write', $permissions);

        $companies = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->distinct()
            ->pluck('inventory')
            ->first();

        if (!$companies) {
            return response()->view('error', ['message' => 'Cannot fetch data'], 400);
        }

        $companyArray = explode(',', $companies);

        $reportType = $request->query('type');

        switch ($reportType) {
            case 'installation':
                $heading = 'Installation Report';
                break;
            case 'uninstallation':
                $heading = 'Uninstallation Report';
                break;
            default:
                $heading = 'Report';
                break;
        }


        $insReport = InstallUninstallReport::select([
            'install_unistall_reports.*',
            'companies.company_name'
        ])
            ->leftJoin('companies', 'install_unistall_reports.company', '=', 'companies.company_id')
            ->whereIn('install_unistall_reports.company', $companyArray)
            ->where('install_unistall_reports.report_type', $reportType)
            ->where('install_unistall_reports.delete_flag', 0)
            ->orderBy('install_unistall_reports.date', 'desc')
            ->get();

        return view('ins-dept.report', compact('insReport', 'heading', 'canWrite', 'reportType'));
    }

    public function fetchReportDetail(Request $request)
    {
        $user = Auth::user();
        $department = "INS";

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $inventoryCompany = DB::table('user_role_permissions')
            ->where('user_id', $user->user_id)
            ->where('department', $department)
            ->distinct()
            ->pluck('inventory')
            ->first();

        $companyArray = explode(',', $inventoryCompany);

        $backUrl = route('ins.home');
        if (!in_array('write', $permissions)) {
            return view('departments.403', compact('backUrl'));
        }

        $reportTypes = ['INSTALLATION', 'UNINSTALLATION'];
        $reportId = $request->input('report-id');
        $requestSource = $request->input('request-source');
        $reportRecord = InstallUninstallReport::find($reportId);

        $companies = DB::table('companies')
            ->where('delete_flag', 0)
            ->orderBy('company_name', 'asc')
            ->get();

        return view('ins-dept.edit-report', compact('reportRecord', 'companies', 'requestSource', 'reportTypes', 'companyArray'));
    }

    public function updateReport(Request $request)
    {
        $response = [
            'success' => false,
            'message' => 'Cannot complete request'
        ];

        if ($request->isMethod('post')) {
            $reportId = $request->input('report-id');
            $report = InstallUninstallReport::find($reportId);

            if (!$report) {
                $response['message'] = 'Report record not found';
                return response()->json($response);
            }

            $report->update([
                'customer' => $request->input('customer'),
                'model' => $request->input('model'),
                'serial_number' => $request->input('serial-number'),
                'asset_code' => $request->input('asset-code'),
                'location' => $request->input('location'),
                'date' => $request->input('date'),
                'technician_name' => $request->input('tech-name'),
                'remarks' => $request->input('remarks'),
                'report_type' => $request->input('report-type'),
                'company' => $request->input('report-company')
            ]);

            $response = [
                'success' => true,
                'message' => 'Report updated successfully.'
            ];
        } else {
            $response['message'] = 'Invalid request method';
        }

        return response()->json($response);
    }
}
