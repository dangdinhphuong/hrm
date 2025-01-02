<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\External\ContactController;
use App\Http\Controllers\External\RequestController;
use App\Http\Controllers\System\PermissionController;
use App\Http\Controllers\System\RoleController;
use App\Http\Controllers\System\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hrm\BankController;
use App\Http\Controllers\Hrm\DepartmentController;
use App\Http\Controllers\Hrm\PositionController;
use App\Http\Controllers\Hrm\EmployeesController;
use App\Http\Controllers\Hrm\CountriesController;
use App\Http\Controllers\Hrm\ContractController;
use App\Http\Controllers\Hrm\JobTitleController;

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

    Route::middleware('check-permission')->group(function () {

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
            ],
            ['except' => 'destroy']
        );

        Route::prefix('employee')->name('employee.')->group(function () {
            Route::prefix('me')->name('me.')->group(function () {
                Route::get('/detail', [EmployeesController::class, 'getMyDetail'])->name('detail');
                Route::get('/contract', [ContractController::class, 'getMyContract'])->name('contract.list');
            });

            Route::get('/{id}/detail', [EmployeesController::class, 'getDetailById'])->name('detail');
            Route::get('/{employeeId}/contract', [ContractController::class, 'getByEmployeesId'])->name('contract.list');
        });

        Route::prefix('contract')->name('contract.')->group(function () {
            Route::post('/', [ContractController::class, 'store'])->name('store');
            Route::post('/{id}', [ContractController::class, 'update'])->name('update');
            Route::get('/{id}', [ContractController::class, 'getDetailById'])->name('detail');
        });
    });

    Route::prefix('employee')->group(function () {
        Route::post('/{employee}/avatar/upload', [EmployeesController::class, 'uploadAvatar']);
        Route::get('/{employee}/avatar', [EmployeesController::class, 'avatar']);
    });
});

Route::prefix('user')->name('users.')->group(function () {
    Route::get('/sale', [UserController::class, 'getUserSale']);
});

Route::get('/download', [UserController::class, 'download']);

// Api External
Route::prefix('external')->middleware(['auth:api-key', 'log-request-incoming'])->group(function () {

    Route::prefix('contacts')->group(function () {
        Route::post('', [ContactController::class, 'create']);
    });

    Route::prefix('requests')->group(function () {
        Route::post('/{type}', [RequestController::class, 'create']);
    });
});

