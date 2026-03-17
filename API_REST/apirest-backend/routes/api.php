<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/users', [UserController::class, 'index']); // acessa no UserController o método index
// Route::post('/users', [UserController::class, 'store']);
Route::apiResource('users', UserController::class)
    ->parameters(['users' => 'user']);
 // Ao invés de usar um por um para cada método essa linha estabelece qual o controlador ficará responsável pelas rotas. Como no user tem as cinco necessárias, o ideal é apenas chamar quem ficará encarregado
