<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Status;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    public function getProjects(Request $request)
    {
        $currentUser = Auth::id();
        $userType = Auth::user()->user_type;
        $department = 'FR';
        $permissions = [];

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $currentUser)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $canWrite = in_array('write', $permissions);

        $inventoryCompanies = DB::table('user_role_permissions')
            ->where('user_id', $currentUser)
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

        $query = Projects::with(['category', 'status'])
            ->select([
                'projects.*',
                'categories.category_name',
                'status.status_name',
                'inventory.company'
            ])
            ->leftJoin('categories', 'projects.category', '=', 'categories.category_id')
            ->leftJoin('status', 'projects.status', '=', 'status.status_id')
            ->leftJoin('inventory', 'projects.inventory_id', '=', 'inventory.inventory_id')
            ->whereIn('inventory.company', $inventoryCompanies)
            ->where('projects.delete_flag', 0);

        if ($request->filled('projectState')) {
            $query->where('projects.state', $request->projectState);
        }

        $projects = $query->orderBy('projects.request_date', 'desc')->get();

        return view('fr-dept.projects', compact('projects', 'canWrite'));
    }

    public function fetchProject(Request $request)
    {
        $projectId = $request->input('project-id');
        $project = Projects::find($projectId);

        $companies = ['DS', 'EDMS', 'PPSL', 'BANKING', 'MDS', 'PROPRINT'];
        $categories = Category::select('category_id', 'category_name')
            ->where('delete_flag', 0)
            ->get();
        $statuses = Status::select('status_id', 'status_name')
            ->where('delete_flag', 0)
            ->get();

        return view('fr-dept.edit-project', compact('project', 'companies', 'categories', 'statuses'));
    }

    public function updateProject(Request $request)
    {
        $validatedData = $request->validate([
            'project-id' => 'required|integer|exists:projects,project_id',
            'request-date' => 'nullable|date',
            'client' => 'nullable|string|max:255',
            'machine-category' => 'nullable|integer|exists:categories,category_id',
            'machine-model' => 'nullable|string|max:255',
            'serial-number' => 'nullable|string|max:255',
            'total-counter' => 'nullable|integer',
            'ac-manager' => 'nullable|string|max:255',
            'priority' => 'nullable|string|max:255',
            'tech-name' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'status-page' => 'nullable|string|max:255',
            'status' => 'nullable|integer|exists:status,status_id',
        ]);

        try {
            $project = Projects::findOrFail($validatedData['project-id']);

            $project->request_date = $validatedData['request-date'];
            $project->client = $validatedData['client'];
            $project->category = $validatedData['machine-category'];
            $project->model = $validatedData['machine-model'];
            $project->serial_number = $validatedData['serial-number'];
            $project->total_counter = $validatedData['total-counter'];
            $project->ac_manager = $validatedData['ac-manager'];
            $project->priority = $validatedData['priority'];
            $project->tech_name = $validatedData['tech-name'];
            $project->deadline = $validatedData['deadline'];
            $project->status_page = $validatedData['status-page'];
            $project->status = $validatedData['status'];

            if ($project->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Project updated successfully.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating project.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating project. ' . $e->getMessage()
            ]);
        }
    }

    public function addProjectForm()
    {

        $currentUser = Auth::id();
        $userType = Auth::user()->user_type;
        $department = 'FR';
        $permissions = [];

        $permissions = DB::table('user_role_permissions')
            ->where('user_id', $currentUser)
            ->where('department', $department)
            ->pluck('permission')
            ->toArray();

        $canWrite = in_array('write', $permissions);

        if (!$canWrite) {
            return redirect()->route('403');
        }

        $categories = Category::where('delete_flag', 0)->get();

        $statuses = Status::where('delete_flag', 0)->get();


        return view('fr-dept.add-project', compact('categories', 'statuses'));
    }

    public function submitProject(Request $request)
    {
        $response = [
            'success' => false,
            'message' => 'Cannot complete request'
        ];

        try {
            $validated = $request->validate([
                'serial_number' => 'required|string',
                'request_date' => 'required|date',
            ]);

            if ($request->isMethod('post')) {
                $serialNumber = $validated['serial_number'];
                $inventory = Inventory::where('serial_number', $serialNumber)->first();
                if ($inventory) {
                    if ($inventory->status !== null) {
                        if ((int)$inventory->status !== 2) {
                            $project = new Projects();
                            $project->inventory_id = $inventory->inventory_id;
                            $project->request_date = $validated['request_date'];
                            $project->client = $inventory->collected_from;
                            $project->category = $inventory->category;
                            $project->model = $inventory->model;
                            $project->serial_number = $inventory->serial_number;
                            $project->total_counter = $inventory->total_counter;
                            $project->ac_manager = $request->input('ac_manager') ?? NULL;
                            $project->priority = $request->input('priority') ?? NULL;
                            $project->tech_name = $request->input('tech_name') ?? NULL;
                            $project->deadline = $request->input('deadline') ?? NULL;
                            $project->status = $inventory->status;

                            if ($project->save()) {
                                $updateInventory = Inventory::where('inventory_id', $inventory->inventory_id)->update([
                                    'is_project' => 1,
                                ]);
                                if ($updateInventory) {
                                    $response = [
                                        'success' => true,
                                        'message' => 'Asset added to projects successfully.'
                                    ];
                                }
                            } else {
                                $response = [
                                    'success' => false,
                                    'message' => 'Unable to add project. Please try again.'
                                ];
                            }
                        } else {
                            $response = [
                                'success' => false,
                                'message' => 'This asset is currently marked as dispatched. Kindly update its status to add it to projects.'
                            ];
                        }
                    } else {
                        $response = [
                            'success' => false,
                            'message' => 'Asset status cannot be null, update the status under inventories to continue.'
                        ];
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Serial number does not exist in the inventory.'
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Invalid request method.'
                ];
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();

            $firstErrorMessage = !empty($errors) ? reset($errors)[0] : 'Validation error';

            $response = [
                'success' => false,
                'message' => $firstErrorMessage
            ];
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'An error occurred.' . $e->getMessage()
            ];
        }

        return response()->json($response);
    }
}
