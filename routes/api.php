<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Hrm\BankController;
use App\Http\Controllers\Hrm\ContractController;
use App\Http\Controllers\Hrm\CountriesController;
use App\Http\Controllers\Hrm\DepartmentController;
use App\Http\Controllers\Hrm\EmployeesController;
use App\Http\Controllers\Hrm\JobTitleController;
use App\Http\Controllers\Hrm\MonthlyTimesheetSummaryController;
use App\Http\Controllers\Hrm\PositionController;
use App\Http\Controllers\Hrm\EmployeeSalaryController;
use App\Http\Controllers\Hrm\TimeSheetController;
use App\Http\Controllers\System\ConfigController;
use App\Http\Controllers\System\PermissionController;
use App\Http\Controllers\System\RoleController;
use App\Http\Controllers\System\UserController;
use App\Http\Controllers\Work\RequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

//Api Internal
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'meInfo']);

    Route::get('/find/users', [UserController::class, 'find']);

    Route::prefix('all')->group(function () {
        Route::get('/roles', [RoleController::class, 'all']);
        Route::get('/users', [UserController::class, 'all']);
        Route::get('/banks', [BankController::class, 'all']);
        Route::get('/departments', [DepartmentController::class, 'all']);
        Route::get('/positions', [PositionController::class, 'all']);
        Route::get('/job-titles', [JobTitleController::class, 'all']);
        Route::get('/countries', [CountriesController::class, 'all']);
    });

    // todo thêm middleware permission: middleware('check-permission')
    Route::name('')->middleware('check-permission')->group(function () {

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/module-group-permission', [RoleController::class, 'getModuleGroupPermission'])->name('module-group-permission');
            Route::patch('/{id}/active', [RoleController::class, 'active'])->name('active');
            Route::patch('/{id}/deactivate', [RoleController::class, 'deactivate'])->name('deactivate');
        });

        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
        });
        Route::apiResources(
            [
                'roles' => RoleController::class,
                'users' => UserController::class,
                'employees' => EmployeesController::class,
                'config' => ConfigController::class,
                'requests' => RequestController::class,
                'salaries' => EmployeeSalaryController::class,
            ],
            ['except' => 'destroy']
        );

        Route::prefix('employee')->name('employee.')->group(function () {
            Route::get('timesheets', [EmployeesController::class, 'getTimesheets']);

            Route::prefix('me')->name('me.')->group(function () {
                Route::get('/detail', [EmployeesController::class, 'getMyDetail'])->name('detail');
                Route::get('/contract', [ContractController::class, 'getMyContract'])->name('contract.list');
                Route::patch('/change-password', [UserController::class, 'updatePassword'])->name('change-password');
            });
            Route::get('/{id}/detail', [EmployeesController::class, 'getDetailById'])->name('detail');
            Route::get('/{employeeId}/contract', [ContractController::class, 'getByEmployeesId'])->name('contract.list');
        });

        Route::prefix('contract')->name('contract.')->group(function () {
            Route::post('/', [ContractController::class, 'store'])->name('store');
            Route::post('/{id}', [ContractController::class, 'update'])->name('update');
            Route::get('/{id}', [ContractController::class, 'getDetailById'])->name('detail');

        });
        Route::prefix('salary')->name('salary.')->group(function () {
            Route::patch('{id}', [EmployeeSalaryController::class, 'update'])->name('update');
            Route::get('find', [EmployeeSalaryController::class, 'find'])->name('detail');
            Route::get('me', [EmployeeSalaryController::class, 'me'])->name('me');
        });

    });

    Route::prefix('employee')->group(function () {
        Route::post('/{employee}/avatar/upload', [EmployeesController::class, 'uploadAvatar']);
        Route::get('/{employee}/avatar', [EmployeesController::class, 'avatar']);
    });
    Route::prefix('monthly-timesheets')->name('')->group(function () {
        Route::get('', [MonthlyTimesheetSummaryController::class, 'index']);
    });
});

Route::prefix('user')->name('users.')->group(function () {
    Route::get('/sale', [UserController::class, 'getUserSale']);
});
Route::get('/download', [UserController::class, 'download']);


// Api External
Route::prefix('external')->middleware(['log-request-incoming'])->group(function () {
    Route::post('/find/employee', [EmployeesController::class, 'getDetailByUsername']);
    Route::post('/attendances', [TimeSheetController::class, 'store']);
    Route::get('/configs', [ConfigController::class, 'index']);
    Route::prefix('monthly-timesheets')->name('')->group(function () {
        Route::get('', [MonthlyTimesheetSummaryController::class, 'index']);
    });
});


