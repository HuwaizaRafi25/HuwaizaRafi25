<?php

use App\Models\MachineOperation;
use App\Models\DailyPayrollHeader;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AllUsersController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PayrollJobController;
use App\Http\Controllers\QcOperationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DailyPayrollController;
use App\Http\Controllers\DesignRequestController;
use App\Http\Controllers\WeeklyPayrollController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MachineOperationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// LANDING PAGE
Route::get('/', function () {
    return view('welcome');
});


// === DASHBOARD === //
Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


//  === USER MANAGEMENT === //

// ALL USERS
Route::get('/user-management/all-users', [AllUsersController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('allUsers.index')->middleware(['auth', 'verified', 'role:admin']);
Route::resource('allUsers', AllUsersController::class);
Route::delete('/users/{id}', [AllUsersController::class, 'destroy'])->name('users.destroy');
Route::get('/users/search', [AllUsersController::class, 'search'])->name('users.search');

// ROLES & PERMISSIONS
Route::get('/user-management/roles-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions.index');
Route::delete('/user-management/roles-permissions/permissions/{id}', [RolePermissionController::class, 'unassignPermission'])->name('unassignPermission');
Route::post('/user-management/roles-permissions/roles', [RolePermissionController::class, 'assignRole'])->name('assignRole');
Route::post('/user-management/roles-permissions/permissions', [RolePermissionController::class, 'assignPermission'])->name('assignPermission');

Route::post('/user-management/roles-permission/unassignRole', [RolePermissionController::class, 'destroy'])->name('unassignRole');
Route::post('/user-management/roles-permission/unassignPermission', [RolePermissionController::class, 'destroy'])->name('unassignPermission');

// === SUPPORT ===
// HELP CENTER
Route::get('/support/help-center', function () {
    return view('menus.support.helpCenter');
})->middleware(['auth', 'verified'])->name('helpCenter');

// === EXPENSES ===
Route::get('/expenses', [ExpensesController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('expenses');
Route::post('/expenses/add-expenses', [ExpensesController::class, 'store'])
    ->name('expenses.store');
Route::put('/expenses/remove-expenses', [ExpensesController::class, 'remove'])
    ->name('expenses.remove');

// === DESIGN REQUESTS ===
Route::get('/design-requests/all-requests', [DesignRequestController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('allRequests');
Route::get('/design-requests/completed-designs', [DesignRequestController::class, 'index2'])
    ->middleware(['auth', 'verified'])
    ->name('completedDesigns');
Route::post('/design-requests/add-requests', [DesignRequestController::class, 'store'])
    ->name('designRequest.store');
Route::post('/design-requests/insert-requests', [DesignRequestController::class, 'insert'])
    ->name('designRequest.insert');
Route::put('/design-requests/approve/{id}', [DesignRequestController::class, 'approve'])
    ->name('designRequest.approve');
Route::get('/design-requests/getDesigns/{id}', [DesignRequestController::class, 'getDesign'])
    ->name('designRequest.getDesign');
Route::put('/design-requests/cancelAllDesigns/{id}', [DesignRequestController::class, 'cancelAll'])
    ->name('designRequest.cancelAll');
Route::put('/design-requests/cancelDesigns', [DesignRequestController::class, 'cancel'])
    ->name('designRequest.cancel');
Route::put('/design-requests/updateRequest', [DesignRequestController::class, 'update'])
    ->name('designRequest.update');



// === DESIGNS ===
Route::get('/designs', [DesignController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('designs');
Route::get('/designs/downloadFile/{design_files}', [DesignController::class, 'download'])->name('design.download');
Route::get('/designs/downloadReference/{reference_image}/{name}', [DesignController::class, 'downloadReference'])->name('designReference.download');
Route::post('/design/upload', [DesignController::class, 'upload'])->name('design.upload');
Route::put('/design/reupload', [DesignController::class, 'reupload'])->name('design.reupload');
Route::put('/design/revision', [DesignController::class, 'revision'])->name('design.revision');
Route::put('/design/approve/{id}', [DesignController::class, 'approve'])
    ->name('design.approve');

// === MACHINE OPERATIONS ===
Route::get('/machine-operations', [MachineOperationController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('machineOperation');
Route::get('/machine-operations/downloadFile/{design_file}/{name}', [MachineOperationController::class, 'download'])->name('designFile.download');
Route::post('/machine-operation/add-operation', [MachineOperationController::class, 'store'])
    ->name('machineOperation.store');


// === QC OPERATIONS ===
Route::get('/qc-operations', [QcOperationController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('qcoperation');
Route::post('/qc-operation/add-operation', [QcOperationController::class, 'store'])
    ->name('qcOperation.store');

// === TRANSACTIONS ===
Route::get('/transactions', [TransactionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('transaction');
Route::get('/getDesignRequests/{id}', [TransactionController::class, 'getDesignRequests'])->name('getDesignRequests');
Route::get('/getDetailTransactions/{id}', [TransactionController::class, 'getDetailTransactions'])->name('getDetailTransactions');
Route::post('/transactions/add_transaction', [TransactionController::class, 'store'])->name('addTransaction');
Route::put('/transaction/confirm/{id}', [TransactionController::class, 'confirm'])
    ->name('transaction.confirm');

// Route::get('/designs/download/{design_files}', [DesignController::class, 'download'])->name('design.download');

// === PAYROLL JOBS ===
Route::get('/payroll/payroll-jobs', [PayrollJobController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('payrolljob');

// === DAILY PAYROLL ===
Route::get('/payroll/daily-payroll', [DailyPayrollController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dailypayroll');

// === WEEKLY PAYROLL ===
Route::get('/payroll/weekly-payroll', [WeeklyPayrollController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('weeklypayroll');
Route::put('/payroll/weekly-payroll/pay', [WeeklyPayrollController::class, 'pay'])->name('weeklypayroll.pay');
Route::get('/payroll/weekly-payroll/getSalary/{id}', [WeeklyPayrollController::class, 'getSalary'])
    ->name('payroll.getSalary');

// === DEBT ===
Route::get('/debt', [DebtController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('debt');
Route::post('/debt/add_debt', [DebtController::class, 'store'])
    ->name('debt.store');
Route::put('/debt/confirm/{id}', [DebtController::class, 'confirm']);

// === AUTH PROFILE ===
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});








// EKSPERIMEN
Route::get('admin', function () {
    notify()->success('Welcome to Laravel Notify ⚡️') or notify()->success('Welcome to Laravel Notify ⚡️', 'My custom title');
    return '<h1>Bang admin kece</h1>';
})->middleware(['auth', 'verified', 'role:admin']);

Route::get('designer', function () {
    notify()->success('Welcome to Laravel Notify ⚡️') or notify()->success('Welcome to Laravel Notify ⚡️', 'My custom title');
    return '<h1>Bang Penulis kul</h1>';
})->middleware(['auth', 'verified', 'role:designer']);

require __DIR__ . '/auth.php';

