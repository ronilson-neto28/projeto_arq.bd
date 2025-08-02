<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\TipoDeRiscoController;
use App\Http\Controllers\RiscoController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('dashboard');
});

Route::group(['prefix' => 'forms'], function(){
    Route::get('cadastrar-funcionario', function () { return view('pages.forms.cadastrar-funcionario'); });
    Route::get('listar-funcionario', function () { return view('pages.forms.listar-funcionario'); });
    Route::get('cadastrar-empresa', function () { return view('pages.forms.cadastrar-empresa'); });
    Route::get('listar-empresa', function () { return view('pages.forms.listar-empresa'); });
});


Route::group(['prefix' => 'icons'], function(){
    Route::get('lucide-icons', function () { return view('pages.icons.lucide-icons'); });
    Route::get('flag-icons', function () { return view('pages.icons.flag-icons'); });
    Route::get('mdi-icons', function () { return view('pages.icons.mdi-icons'); });
});

Route::group(['prefix' => 'general'], function(){
    Route::get('profile', function () { return view('pages.general.profile'); });
});

Route::group(['prefix' => 'auth'], function(){
    //Route::get('login', function () { return view('pages.auth.login'); });
    //Route::get('register', function () { return view('pages.auth.register'); });
    // Telas
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

    // Processamento de formulário
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
    Route::post('register', [RegisterController::class, 'register'])->name('register.post');
    

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

});

Route::prefix('empresas')->group(function () {
    Route::get('cadastrar', [EmpresaController::class, 'create'])->name('empresas.create');
    Route::post('cadastrar', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('listar', [EmpresaController::class, 'index'])->name('empresas.index'); // opcional
});

Route::prefix('funcionarios')->group(function () {
    Route::get('cadastrar', [FuncionarioController::class, 'create'])->name('funcionarios.create');
    Route::post('cadastrar', [FuncionarioController::class, 'store'])->name('funcionarios.store');
    Route::get('listar', [FuncionarioController::class, 'index'])->name('funcionarios.index'); // se quiser listar depois
});

Route::prefix('cargos')->group(function () {
    Route::get('cadastrar', [CargoController::class, 'create'])->name('cargos.create');
    Route::post('cadastrar', [CargoController::class, 'store'])->name('cargos.store');
    Route::get('listar', [CargoController::class, 'index'])->name('cargos.index'); // opcional
});

Route::prefix('tipos-de-risco')->group(function () {
    Route::get('cadastrar', [TipoDeRiscoController::class, 'create'])->name('tipos_risco.create');
    Route::post('cadastrar', [TipoDeRiscoController::class, 'store'])->name('tipos_risco.store');
    Route::get('listar', [TipoDeRiscoController::class, 'index'])->name('tipos_risco.index'); // opcional
});

Route::prefix('riscos')->group(function () {
    Route::get('cadastrar', [RiscoController::class, 'create'])->name('riscos.create');
    Route::post('cadastrar', [RiscoController::class, 'store'])->name('riscos.store');
    Route::get('listar', [RiscoController::class, 'index'])->name('riscos.index'); // opcional
});

Route::get('/', [DashboardController::class, 'index']);
























Route::group(['prefix' => 'error'], function(){
    Route::get('404', function () { return view('pages.error.404'); });
    Route::get('500', function () { return view('pages.error.500'); });
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');
