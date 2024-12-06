<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    public function getStatuses()
    {
        $statuses = Status::where('delete_flag', 0)
            ->get(['status_id as id', 'status_name as name']);

        return response()->json(['statuses' => $statuses]);
    }

    public function addStatus(Request $request)
    {
        $response = [
            'success' => false,
            'message' => 'Cannot complete request'
        ];
        if (Auth::check() && Auth::user()->type == 2) {
            if ($request->isMethod('post')) {
                $statusName = $request->input('status-name');

                try {
                    $status = new Status();
                    $status->status_name = $statusName;
                    $status->save();

                    $response = [
                        'success' => true,
                        'message' => 'Status added successfully.'
                    ];
                } catch (\Exception $e) {
                    Log::error('Error adding status: ' . $e->getMessage());
                    $response = [
                        'success' => false,
                        'message' => $e->getMessage()
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Invalid request method'
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Cannot process your request'
            ];
        }


        return response()->json($response);
    }
}
