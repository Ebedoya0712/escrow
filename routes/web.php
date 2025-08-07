<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS (INVITADOS) ---
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/registro', [AuthController::class, 'storeRegistration']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'storeLogin']);
});


Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/transacciones', [AdminController::class, 'index'])->name('transactions.index');
        Route::get('/transacciones/{transaction}', [AdminController::class, 'show'])->name('transactions.show');
        Route::post('/transacciones/{transaction}/join', [AdminController::class, 'joinChat'])->name('transactions.joinChat');
        Route::post('/transacciones/{transaction}/update-status', [AdminController::class, 'updateStatus'])->name('transactions.updateStatus');
    });

// --- RUTAS PROTEGIDAS (USUARIOS AUTENTICADOS) ---
Route::middleware('auth')->group(function () {
    
    // Ruta del Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



    Route::resource('transactions', TransactionController::class)
            ->names([
                'index' => 'transacciones.index',
                'create' => 'transacciones.create',
                'store' => 'transacciones.store',
                'show' => 'transacciones.show',
                'edit' => 'transacciones.edit',
                'update' => 'transacciones.update',
                'destroy' => 'transacciones.destroy',
            ]);

            Route::get('/invitacion/{transaction:uuid}', [TransactionController::class, 'showInvitation'])
            ->name('transactions.invitation');

// --- RUTA PROTEGIDA PARA ACEPTAR LA INVITACIÓN ---
// El usuario debe haber iniciado sesión para poder aceptar
            Route::post('/invitacion/{transaction:uuid}/aceptar', [TransactionController::class, 'acceptInvitation'])
            ->middleware('auth')
            ->name('transactions.accept');

            Route::post('/transactions/{transaction}/messages', [MessageController::class, 'store'])->name('messages.store');
    
            // NUEVA RUTA: Ruta para BUSCAR mensajes nuevos para el chat dinámico (GET)
            Route::get('/transactions/{transaction}/messages', [MessageController::class, 'fetch'])->name('messages.fetch');

            Route::post('transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])
            ->name('transacciones.cancel');

            Route::prefix('perfil')->name('profile.')->group(function () {
        // Ruta para MOSTRAR la página principal del perfil
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        
        // Ruta para ACTUALIZAR los datos del perfil (nombre, email)
        Route::put('/details', [ProfileController::class, 'updateDetails'])->name('update.details');

        // Ruta para ACTUALIZAR la contraseña
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update.password');
            });

            Route::prefix('perfil')->name('profile.')->group(function () {
            Route::get('/metodos-pago', [ProfileController::class, 'paymentMethods'])->name('payment.index');
            Route::post('/metodos-pago', [ProfileController::class, 'storePaymentMethod'])->name('payment.store');
            Route::delete('/metodos-pago/{method}', [ProfileController::class, 'destroyPaymentMethod'])->name('payment.destroy');
        });

    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});




// Ruta de la página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('home');
