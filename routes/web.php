<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminPanel\AdminController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\CdRecordController;
use App\Http\Controllers\InsController;
use App\Models\Inventory;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'type:1'])->group(
    function () {
        Route::get('/admin', [AdminController::class, 'stats'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'getUsers'])->name('admin.getUsers');
        Route::post('/admin/update-user', [AdminController::class, 'updateUser'])->name('admin.updateUser');
        Route::get('/admin/change-password', [AdminController::class, 'passwordForm'])->name('admin.passwordForm');
        Route::post('/admin/change-password', [AdminController::class, 'changePassword'])->name('admin.updatePassword');
        Route::get('/admin/roles', [AdminController::class, 'getRoles'])->name('admin.getRoles');
        Route::get('/admin/create-role', [AdminController::class, 'roleForm'])->name('admin.roleForm');
        Route::post('/admin/create-role', [AdminController::class, 'createRole'])->name('admin.createRole');
        Route::get('/admin/create-user', [AdminController::class, 'createUserForm'])->name('create.userForm');
        Route::post('/admin/create-user/{type}', [AdminController::class, 'createUser'])->name('admin.createUser');
    }
);

Route::middleware(['auth', 'type:2'])->group(function () {
    Route::get('/departments', [DepartmentController::class, 'index'])->name('department.home');
    Route::get('/403', [DepartmentController::class, 'unauthorized'])->name('error.403');
    Route::get('/download-file', [DepartmentController::class, 'downloadFile'])->name('download.file');

    Route::prefix('fr-dept')->middleware('check.department:FR')->group(function () {
        Route::get('/', [DepartmentController::class, 'frDashboard'])->name('fr.home');
        Route::get('/get-categories', [CategoryController::class, 'getCategories'])->name('department.getCategories');
        Route::post('/add-category', [CategoryController::class, 'addCategory'])->name('fr.addCategory');
        Route::get('/get-statuses', [StatusController::class, 'getStatuses'])->name('department.getStatuses');
        Route::post('/add-status', [StatusController::class, 'addStatus'])->name('fr.addStatus');
        Route::get('/inventory-stats', [InventoryController::class, 'getInventoryStats'])->name('inventory.stats');
        Route::get('/get-years', [InventoryController::class, 'getYears'])->name('get.years');
        Route::get('/inventory-report', [InventoryController::class, 'getInventoryReport'])->name('inventory-report');
        Route::post('/export-report', [InventoryController::class, 'exportReport'])->name('export-report');
        Route::post('/edit-inventory', [InventoryController::class, 'fetchInventoryDetail'])->name('fetch-inventory-detail');
        Route::post('/update-inventory', [InventoryController::class, 'updateInventory'])->name('update-inventory');
        Route::post('/delete-inventory', [InventoryController::class, 'deleteInventory'])->name('delete-inventory');
        // Route::get('/add-inventory', [InventoryController::class, 'getInventoryForm'])->name('inventory.form');
        Route::post('/submit-inventory', [InventoryController::class, 'submitInventory'])->name('fr.submit-inventory');
        Route::get('/inventories', [InventoryController::class, 'fetchInventories'])->name('fr.fetchInventories');
        Route::post('/submit-excel', [InventoryController::class, 'submitExcel'])->name('fr.submit-excel');
        Route::get('/projects', [ProjectController::class, 'getProjects'])->name('get.projects');
        Route::post('/edit-project', [ProjectController::class, 'fetchProject'])->name('edit.project');
        Route::get('/add-project', [ProjectController::class, 'addProjectForm'])->name('get.project-form');
        Route::post('/submit-project', [ProjectController::class, 'submitProject'])->name('submit.project');
        Route::post('/update-project', [ProjectController::class, 'updateProject'])->name('update-project');
        Route::post('/export-report', [ReportController::class, 'exportReport'])->name('export.report');
        Route::get('/fr-dept/fetch-history', [InventoryController::class, 'getHistory'])->name('history.search');
    });

    Route::prefix('cd-dept')->middleware('check.department:DC')->group(function () {
        Route::get('/', [DepartmentController::class, 'cdDashboard'])->name('cd.home');
        Route::get('/add-record', [CdRecordController::class, 'getRecordForm'])->name('get.record-form');
        Route::get('/records', [CdRecordController::class, 'getRecords'])->name('record.report');
        Route::post('/submit-record', [CdRecordController::class, 'submitRecord'])->name('cd.submit-record');
        Route::post('/edit-record', [CdRecordController::class, 'fetchRecord'])->name('cd.edit-record');
        Route::post('/update-record', [CdRecordController::class, 'updateRecord'])->name('update-record');
    });

    Route::prefix('ins-dept')->middleware('check.department:INS')->group(function () {
        Route::get('/', [DepartmentController::class, 'insDashboard'])->name('ins.home');
        Route::get('/add-report', [InsController::class, 'getReportForm'])->name('get.report-form');
        Route::post('/submit-report', [InsController::class, 'submitReport'])->name('submit-report');
        Route::get('/report', [InsController::class, 'getReport'])->name('ins.report');
        Route::post('/edit-report', [InsController::class, 'fetchReportDetail'])->name('ins.edit-report');
        Route::post('/update-report', [InsController::class, 'updateReport'])->name('ins.update-report');
    });
});


// Route::middleware(['auth', 'type:2'])->group(
//     function () {
//         Route::get('/departments', [DepartmentController::class, 'index'])->name('department.home');
//         Route::get('/fr-dept', [DepartmentController::class, 'frDashboard'])->name('fr.home');

//         Route::get('/403', [DepartmentController::class, 'unauthorized'])->name('error.403');

//         Route::get('/fr-dept/get-categories', [CategoryController::class, 'getCategories'])->name('department.getCategories');
//         Route::post('/fr-dept/add-category', [CategoryController::class, 'addCategory'])->name('fr.addCategory');

//         Route::get('/fr-dept/get-statuses', [StatusController::class, 'getStatuses'])->name('department.getStatuses');
//         Route::post('/fr-dept/add-status', [StatusController::class, 'addStatus'])->name('fr.addStatus');
//         Route::get('/fr-dept/inventory-stats', [InventoryController::class, 'getInventoryStats'])->name('inventory.stats');
//         Route::get('/fr-dept/get-years', [InventoryController::class, 'getYears'])->name('get.years');
//         Route::get('/fr-dept/inventory-report', [InventoryController::class, 'getInventoryReport'])->name('inventory-report');
//         Route::post('/fr-dept/export-report', [InventoryController::class, 'exportReport'])->name('export-report');
//         Route::post('/fr-dept/edit-inventory', [InventoryController::class, 'fetchInventoryDetail'])->name('fetch-inventory-detail');
//         Route::post('/fr-dept/update-inventory', [InventoryController::class, 'updateInventory'])->name('update-inventory');
//         Route::get('/fr-dept/add-inventory', [InventoryController::class, 'getInventoryForm'])->name('inventory.form');
//         Route::post('/fr-dept/submit-inventory', [InventoryController::class, 'submitInventory'])->name('fr.submit-inventory');
//         Route::get('/fr-dept/inventories', [InventoryController::class, 'fetchInventories'])->name('fr.fetchInventories');
//         Route::post('/fr-dept/submit-excel', [InventoryController::class, 'submitExcel'])->name('fr.submit-excel');

//         Route::get('/download-file', [DepartmentController::class, 'downloadFile'])->name('download.file');

//         Route::get('/fr-dept/projects', [ProjectController::class, 'getProjects'])->name('get.projects');
//         Route::post('/fr-dept/edit-project', [ProjectController::class, 'fetchProject'])->name('edit.project');
//         Route::get('/fr-dept/add-project', [ProjectController::class, 'addProjectForm'])->name('get.project-form');
//         Route::post('/fr-dept/submit-project', [ProjectController::class, 'submitProject'])->name('submit.project');
//         Route::post('/fr-dept/update-project', [ProjectController::class, 'updateProject'])->name('update-project');
//         Route::post('/fr-dept/export-report', [ReportController::class, 'exportReport'])->name('export.report');


//         Route::get('/cd-dept', [DepartmentController::class, 'cdDashboard'])->name('cd.home');
//         Route::get('/cd-dept/add-record', [CdRecordController::class, 'getRecordForm'])->name('get.record-form');
//         Route::get('/cd-dept/records', [CdRecordController::class, 'getRecords'])->name('record.report');
//         Route::post('/cd-dept/submit-record', [CdRecordController::class, 'submitRecord'])->name('cd.submit-record');
//         Route::post('/cd-dept/edit-record', [CdRecordController::class, 'fetchRecord'])->name('cd.edit-record');
//         Route::post('/cd-dept/update-record', [CdRecordController::class, 'updateRecord'])->name('update-record');

//         Route::get('/ins-dept', [DepartmentController::class, 'insDashboard'])->name('ins.home');
//         Route::get('/ins-dept/add-report', [InsController::class, 'getReportForm'])->name('get.report-form');
//         Route::post('/ins-dept/submit-report', [InsController::class, 'submitReport'])->name('submit-report');
//         Route::get('/ins-dept/report', [InsController::class, 'getReport'])->name('ins.report');
//         Route::post('/ins-dept/edit-report', [InsController::class, 'fetchReportDetail'])->name('ins.edit-report');
//         Route::post('/ins-dept/update-report', [InsController::class, 'updateReport'])->name('ins.update-report');
//     }
// );


Route::get('/phpinfo', function () {
    phpinfo();
});
