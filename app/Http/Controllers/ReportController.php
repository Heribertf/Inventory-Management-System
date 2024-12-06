<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projects;
use App\Models\Inventory;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use FPDF;

class ReportController extends Controller
{
    public function exportReport(Request $request)
    {
        $statusReport = $request->input('export_excel') ?? $request->input('export_pdf');
        $exportStateCondition = $request->input('exportProjectState') ?? '';

        if ($statusReport === 'projects') {
            $data = $this->fetchProjects($exportStateCondition);
        } else {
            $data = $this->fetchData($statusReport);
        }

        if ($data === null || (is_array($data) && empty($data))) {
            echo "No data to export";
            exit;
            // return response()->json(['message' => 'No data to export'], 404);
        }else{
           $headers = array_keys($data[0]);
        }

        // $headers = array_keys($data[0]);

        if ($request->has('export_excel')) {
            return $this->exportToExcel($data, $headers, $statusReport);
        } elseif ($request->has('export_pdf')) {
            return $this->exportToPDF($data, $headers);
        }
    }
    private function fetchData($statusReport)
    {
        return Inventory::with(['category','status'])
            ->select([
                'collection_date',
                'collected_from',
                'company',
                'categories.category_name',
                'model',
                'serial_number',
                'status.status_name',
                'dp_model',
                'dp_serial',
                'cb',
                'color',
                'mono_counter',
                'total',
                'fk',
                'dk',
                'dv',
                'belt',
                'feed',
                'dispatched_to',
                'dispatch_date',
                'warehouse',
                'dp_pf_out',
                'life_counter',
                'remarks'
            ])
            ->leftJoin('categories', 'inventory.category', '=', 'categories.category_id')
            ->leftJoin('status', 'inventory.status', '=', 'status.status_id')
            ->where('inventory.status', $statusReport)
            ->where('inventory.delete_flag', 0)
            ->orderBy('inventory.collection_date', 'DESC')
            ->get()
            ->map(function ($inventory) {
                return [
                    'collection_date' => $inventory->collection_date,
                    'collected_from' => $inventory->collected_from,
                    'company' => $inventory->company,
                    'category_name' => $inventory->category_name,
                    'model' => $inventory->model,
                    'serial_number' => $inventory->serial_number,
                    'status' => $inventory->status_name,
                    'dp_model' => $inventory->dp_model,
                    'dp_serial' => $inventory->dp_serial,
                    'cb' => $inventory->cb,
                    'color' => $inventory->color,
                    'mono_counter' => $inventory->mono_counter,
                    'total' => $inventory->total,
                    'fk' => $inventory->fk,
                    'dk' => $inventory->dk,
                    'dv' => $inventory->dv,
                    'belt' => $inventory->belt,
                    'feed' => $inventory->feed,
                    'dispatched_to' => $inventory->dispatched_to,
                    'dispatch_date' => $inventory->dispatch_date,
                    'warehouse' => $inventory->warehouse,
                    'dp_pf_out' => $inventory->dp_pf_out,
                    'life_counter' => $inventory->life_counter,
                    'remarks' => $inventory->remarks,
                ];
            })
            ->toArray();
    }


    private function fetchProjects($exportStateCondition)
    {
        return Projects::with(['category', 'status'])
            ->select([
                'request_date',
                'client',
                'categories.category_name',
                'model',
                'serial_number',
                'total_counter',
                'ac_manager',
                'priority',
                'tech_name',
                'deadline',
                'days_left',
                'state',
                'status_page',
                'status.status_name'
            ])
            ->leftJoin('categories', 'projects.category', '=', 'categories.category_id')
            ->leftJoin('status', 'projects.status', '=', 'status.status_id')
            ->where('projects.delete_flag', 0)
            ->when($exportStateCondition, function ($query) use ($exportStateCondition) {
                return $query->where('projects.state', $exportStateCondition);
            })
            ->orderBy('projects.request_date', 'DESC')
            ->get()
            ->map(function ($project) {
                return [
                    'request_date' => $project->request_date,
                    'client' => $project->client,
                    'category_name' => $project->category_name ?? null,
                    'model' => $project->model,
                    'serial_number' => $project->serial_number,
                    'total_counter' => $project->total_counter,
                    'ac_manager' => $project->ac_manager,
                    'priority' => $project->priority,
                    'tech_name' => $project->tech_name,
                    'deadline' => $project->deadline,
                    'days_left' => $project->days_left,
                    'state' => $project->state,
                    'status_page' => $project->status_page,
                    'status' => $project->status_name,
                ];
            })
            ->toArray();
    }


    private function exportToExcel($data, $headers, $statusReport)
    {
        $fileName = $statusReport === 'projects' ? 'Projects' : $this->getFileName($statusReport);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column.'1', $header);
            $column++;
        }

        // Set data
        $row = 2;
        foreach ($data as $dataRow) {
            $column = 'A';
            foreach ($dataRow as $cell) {
                $sheet->setCellValue($column.$row, $cell);
                $column++;
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    private function exportToPDF($data, $headers)
    {
        $pdf = new FPDF('L', 'mm', 'A3');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 8);
        $headers = array_map('strtoupper', $headers);

        foreach ($headers as $header) {
            $pdf->Cell(40, 7, $header, 1);
        }
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);

        foreach ($data as $row) {
            foreach ($row as $cell) {
                $pdf->Cell(40, 6, $cell, 1);
            }
            $pdf->Ln();
        }

        $pdf->Output('D', 'machine-status-report.pdf');
        exit;
    }

    private function getFileName($statusReport)
    {
        return match ($statusReport) {
            2 => "Dispatched-Machines",
            3 => "Disposed-Machines",
            1 => "Collected-Machines",
            7 => "Disposable-Machines",
            6 => "Machines-For-Spare",
            4 => "Ready-Machines",
            5 => "Serviceable-Machines",
            default => "Machine-Report",
        };
    }
}
