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
        Route::get('/user-materials', [MaterialController::class, 'showAllMaterials'])
            ->name('user.material');

            Route::get('/user-material/{materialId}/projects', [MaterialController::class, 'showMaterialProjects'])
                ->name('user.material.project');

        // MATERIAL ROUTES

        //Barra de Pesquisa
        Route::get('/materials', [MaterialController::class, 'search'])
            ->name('dashboard');

        // Route::post('/material/approve/{material}', 'MaterialController@approve')->name('material.approve');

        // Route::post('/material/reject/{material}', 'MaterialController@reject')->name('material.reject');

        Route::get('/material-info/project/{projectId}/material/{materialId}/{warehouseId}/{cabinetId}', [MaterialController::class, 'showMaterial'])
            ->name('material.show');

            // Aquisição
            Route::post('/acquire-material/project/{projectId}/material/{materialId}/{warehouseId}/{cabinetId}', [MaterialController::class, 'acquire'])->name('acquire.material');

            // Devolução
            Route::post('/return-material/project/{projectId}/{materialId}/{warehouseId}/{cabinetId}', [MaterialController::class, 'return'])->name('material.return'); 

            // BuyHistory
            Route::get('/purchases/material/{material}', [MaterialController::class, 'showHistoricoCompras'])
            ->name('material.historico-compras');

        //Project Routes
        Route::get('/projects', [ProjectController::class, 'index'])
            ->name('admin.manage.project');

        Route::post('/projects/store', [ProjectController::class, 'store'])
            ->name('admin.store.project');

        Route::get('/project/{projectId}/edit', [ProjectController::class, 'edit'])
            ->name('admin.edit.project');

        Route::get('/projects/{projectId}/remove', [ProjectController::class, 'remove'])
            ->name('admin.remove.project');

            //Owners and Members
            Route::post('/project/owner-update', [ProjectController::class, 'switchOwner'])
                ->name('project.owner.update');

            Route::post('/project/{projectId}/add-member', [ProjectController::class, 'addMember'])
                ->name('project.add.member');

            Route::get('/project/{projectId}/remove-member/{userId}', [ProjectController::class, 'removeMember'])
                ->name('project.remove.member');

            // Materials
            Route::post('/project/{projectId}/add-material', [ProjectController::class, 'addMaterial'])
                ->name('project.add.material');

            Route::get('/project/{projectId}/remove-material/{materialId}', [ProjectController::class, 'removeMaterial'])
                ->name('project.remove.material');


        // Account Routes
        Route::get('/myaccount/{user}', [UserController::class, 'myAccount'])   
            ->name('my.account');

            // Password 
        Route::get('/myaccount/update-password/{user}', [UserController::class, 'updatePassword'])
            ->name('password.update');

        //ADMIN ROUTES
        Route::get('/admin-materials', [MaterialController::class, 'showAllMaterials'])
            ->name('admin.material');

        Route::get('/admin-material/{materialId}/projects', [MaterialController::class, 'showMaterialProjects'])
            ->name('admin.material.project');

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
        Route::get('/warehouses/{warehouse}/cabinets/edit', [CabinetController::class, 'edit'])
            ->name('admin.cabinets.edit');

        Route::get('/warehouses/{warehouse}/cabinets/remove', [CabinetController::class, 'remove'])
            ->name('admin.cabinets.remove');

        Route::get('warehouses/{warehouse}/cabinets/create',[CabinetController::class, 'create'])
            ->name('admin.cabinets.create');

        Route::post('warehouses/{warehouse}/cabinets/store', [CabinetController::class, 'store'])
            ->name('admin.cabinets.store');

            //STOCK FILL
        Route::post('warehouses/cabinets/stock-movement', [WarehouseController::class, 'warehouseStockMovement'])
            ->name('admin.warehouses.stockmovement');
        
            //LOCATION ARROWS
        Route::post('/update-location/shelf/{warehouseId}', [CabinetController::class, 'updateLocation'])
            ->name('admin.update.shelf');

        Route::post('/update-location/warehouse/{warehouse}', [WarehouseController::class, 'updateLocation'])
            ->name('admin.update.warehouse');

        //EMAIL ROUTES
        Route::get('/material/movement/reject/{material}/{movementId}/{token}', [MaterialController::class,'showRejectionForm'])->name('material.movement.reject.form');

        Route::post('/material/movement/reject/{material}/{movementId}/{token}', [MaterialController::class,'rejectMovement'])->name('material.movement.reject');
    });
});








