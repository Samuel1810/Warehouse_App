<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MaterialController;
use App\Models\Material;
use App\Models\Project;
use App\Models\User;
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

// Rota de fallback
    Route::fallback(function () {
        return 'Página não encontrada. Você deve estar autenticado para acessar esta página.';
    });

// Rotas públicas (não requerem autenticação)
Route::get('/', function () {
    return redirect('welcome');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Rotas de autenticação
Route::group(['middleware' => 'web'], function () {
    // LOG ROUTES
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login.index');
        Route::post('/login', 'store')->name('login.store');
        Route::get('/logout', 'destroy')->name('login.destroy');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'index')->name('register.index');
        Route::post('/register', 'store')->name('register.store');
    });

    // Rotas autenticadas
    Route::middleware(['auth'])->group(function () {
        // USER ROUTES

        Route::get('/user-projects', [MaterialController::class, 'index'])
            ->name('user.project');

        Route::get('/user-materials/project/{project}', [MaterialController::class, 'materialProjects'])
            ->name('user.project.material');

        // MATERIAL ROUTES

        Route::get('/purchases/material/{material}', [MaterialController::class, 'showHistoricoCompras'])
            ->name('material.historico-compras');

        Route::get('/material-acquisition/project/{project}/material/{material}', [MaterialController::class, 'purchases'])
            ->name('material.acquisition');

        Route::post('/material/approve/{material}', 'MaterialController@approve')->name('material.approve');

        Route::post('/material/reject/{material}', 'MaterialController@reject')->name('material.reject');

            // Aquisição
        Route::post('/acquired-material/project/{project}/material/{material}', [MaterialController::class, 'acquired'])->name('acquired.material');

            // Devolução
        Route::post('/acquired-material/{material}/return', [MaterialController::class, 'returnMaterial'])->name('material.return'); 

        // Account Routes
        Route::get('/myaccount/{user}', [UserController::class, 'myAccount'])   ->name('my.account');

            // Password 
        Route::get('/myaccount/update-password/{user}', [UserController::class, 'updatePassword'])
            ->name('password.update');

        //ADMIN ROUTES
        Route::get('/admin-projects', [MaterialController::class, 'index'])
            ->name('admin.project');

        Route::get('/admin-materials/project/{project}', [MaterialController::class, 'materialProjects'])
            ->name('admin.project.material');

        Route::get('/admin-page', function(){
            $users = User::where('user_type', 0)->get();

            return view('admin-page', [
            'users' => $users
            ]);
        })->name('admin.page');

            //STOCK
        Route::get('/stock', [MaterialController::class, 'stock'])
            ->name('admin.stock');

        Route::get('/stock/{material}/edit', [MaterialController::class, 'editMaterial'])
            ->name('admin.stock.edit');

        Route::post('/stock/{material}/update', [MaterialController::class,'updateMaterial'])
            ->name('admin.stock.update');

        Route::post('/stock/add-purchase/{material}', [MaterialController::class, 'storePurchase'])
            ->name('admin.stock.store');

        Route::post('/stock/devolution/{material}', [MaterialController::class, 'storeDevolution'])
            ->name('admin.stock.devolution');

            //USER MANAGEMENT

        Route::get('/admin-page/{user}', [UserController::class, 'showUserMovements'])
            ->name('admin.usermovements');

            //WAREHOUSES
        Route::get('/warehouses', [WarehouseController::class, 'index'])
            ->name('admin.warehouses.index');
            
        Route::get('/warehouses/create', [WarehouseController::class, 'create'])
            ->name('admin.warehouses.create');

        Route::post('/warehouses/store', [WarehouseController::class, 'store'])
            ->name('admin.warehouses.store');
        
        Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])
            ->name('admin.warehouses.edit');

        Route::get('/warehouses/{warehouse}/remove', [WarehouseController::class, 'remove'])
        ->name('admin.warehouses.remove');

            //CABINETS
        Route::get('/warehouses/{warehouse}/shelves/edit', [CabinetController::class, 'edit'])
            ->name('admin.cabinets.edit');

        Route::get('/warehouses/{warehouse}/shelves/remove', [CabinetController::class, 'remove'])
            ->name('admin.cabinets.remove');

        Route::get('warehouses/{warehouse}/shelves/create',[CabinetController::class, 'create'])
            ->name('admin.cabinets.create');

        Route::post('warehouses/{warehouse}/shelves/store', [CabinetController::class, 'store'])
            ->name('admin.cabinets.store');

            //STOCK FILL
        Route::post('warehouses/cabinets/stock-movement', [WarehouseController::class, 'warehouseStockMovement'])
            ->name('admin.warehouses.stockmovement');
        
            //LOCATION ARROWS
        Route::post('/update-location/shelf/{warehouseId}', [CabinetController::class, 'updateLocation'])
            ->name('admin.update.shelf');

        Route::post('/update-location/warehouse/{warehouse}', [WarehouseController::class, 'updateLocation'])
            ->name('admin.update.warehouse');

        Route::get('/projects', [ProjectController::class, 'index'])
            ->name('admin.manage.project');

        Route::get('/project/{projectId}/edit', [ProjectController::class, 'edit'])
            ->name('admin.edit.project');
        
        //EMAIL ROUTES
        Route::get('/material/movement/reject/{material}/{movementId}/{token}', [MaterialController::class,'showRejectionForm'])->name('material.movement.reject.form');

        Route::post('/material/movement/reject/{material}/{movementId}/{token}', [MaterialController::class,'rejectMovement'])->name('material.movement.reject');
    });
});








