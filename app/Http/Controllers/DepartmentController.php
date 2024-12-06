<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DepartmentController extends Controller
{
    public function index()
    {
        $departmentsArray = session('departments');

        return view('departments.home', [
            'departmentsArray' => $departmentsArray,
        ]);
    }

    public function unauthorized()
    {
        return view('departments.403');
    }
    public function frDashboard()
    {
        return view('fr-dept.home');
    }

    public function cdDashboard()
    {
        return view('cd-dept.home');
    }

    public function insDashboard()
    {
        return view('ins-dept.home');
    }

    public function downloadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|string'
        ]);

        $fileName = $request->input('file');
        // $actualName = substr($fileName, 8);

        // dd($actualName);
        $filePath = 'public/' . $fileName;

        if (!Storage::exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found.'
            ], 404);
        }

        return Storage::download($filePath);
    }
}
