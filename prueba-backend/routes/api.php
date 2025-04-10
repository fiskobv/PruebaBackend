<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PrecioProductoController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Autenticación - generación de token
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Las credenciales son incorrectas.'],
        ]);
    }

    return response()->json([
        'token' => $user->createToken('token-personal')->plainTextToken,
    ]);
});

// Ruta temporal para pruebas de conectividad
// Route::get('/test-api', function () {
//     return response()->json(['message' => 'Ruta de prueba']);
// });

// Rutas protegidas con autenticación Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Productos
    Route::get('/products', [ProductoController::class, 'index']);
    Route::post('/products', [ProductoController::class, 'store']);
    Route::get('/products/{id}', [ProductoController::class, 'show']);
    Route::put('/products/{id}', [ProductoController::class, 'update']);
    Route::delete('/products/{id}', [ProductoController::class, 'destroy']);

    // Precios
    Route::get('/products/{id}/prices', [PrecioProductoController::class, 'index']);
    Route::post('/products/{id}/prices', [PrecioProductoController::class, 'store']);

    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
