<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//autenticação server side

Route::post('/login', function(Request $req){

    $credentials = [
        'email' => 'hackett.demetris@example.net',
        'password' => 'password'
    ];

    if(Auth::attempt($credentials)) {
        request()->session()->regenerate();

        return auth()->user();
    }

    abort(401);
});

Route::get('/nomes', function(){
    return response()->json(User::all(),200);
})->middleware('auth:sanctum');

Route::get('/', function () {
    return view('welcome');

});
