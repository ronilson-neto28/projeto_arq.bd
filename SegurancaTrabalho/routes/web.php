<?php



use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\Api\RiscoExameController;
use App\Http\Controllers\EncaminhamentoController;
use App\Http\Controllers\AsoController;


////////////////////////////////////////
// ROTAS PARA NÃO AUTENTICADOS (guest)
////////////////////////////////////////

/*
// Rota raiz para usuários não autenticados - redireciona para login
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');
*/

Route::get('/test-mail', function () {
    Mail::raw('Teste de e-mail via Mailpit 🚀', function ($m) {
        $m->to('teste@exemplo.local')->subject('Mailpit OK');
    });
    return 'OK';
});

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login');

Route::get('/mongo/health', function () {
    try {
        $count = DB::table('users')->count();
        return response()->json(['status' => 'ok', 'users_count' => $count]);
    } catch (\Throwable $e) {
        return response()->json(['status' => 'erro', 'message' => $e->getMessage()], 500);
    }
});

Route::post('/mongo/users', function (\Illuminate\Http\Request $request) {
    $data = [
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => $request->input('password'),
    ];
    if (!$data['name'] || !$data['email'] || !$data['password']) {
        return response()->json(['status' => 'erro', 'message' => 'name, email, password são obrigatórios'], 422);
    }
    $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
    DB::table('users')->insert($data);
    return response()->json(['status' => 'ok']);
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/mongo/users', function () {
    $users = DB::table('users')->select('name','email')->limit(10)->get();
    return response()->json(['status' => 'ok', 'usuarios' => $users]);
});


/*
Route::middleware('guest')->prefix('auth')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.post');

    // Rotas de verificação de email
    Route::get('verify-code', [RegisterController::class, 'showVerificationForm'])->name('verification.show');
    Route::post('verify-code', [RegisterController::class, 'verifyCode'])->name('verification.verify');
    Route::post('resend-code', [RegisterController::class, 'resendCode'])->name('verification.resend');

    // Exibe o formulário de esqueci a senha
    Route::get('/forgot-password', [ForgotSenhaController::class, 'showLinkRequestForm'])
        ->name('password.request');

    // Envia o e-mail com o link de redefinição
    Route::post('/forgot-password', [ForgotSenhaController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    // Link de redefinição recebido por e-mail (Laravel exige esse name: password.reset)
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

    // Submete nova senha
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('CodigoRecuperacaoEmail', [CodeConfirmationController::class, 'index'])->name('codigoRecuperação.index');
    Route::post('CodigoRecuperacaoEmail', [CodeConfirmationController::class, 'store'])->name('codigoRecuperação.store');
    
});
*/

////////////////////////////////////////
// ROTAS AUTENTICADAS (auth obrigatório)
////////////////////////////////////////
/*
Route::middleware('auth')->group(function () {
    // DASHBOARD
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // LOGOUT
    Route::post('/auth/logout', [LoginController::class, 'logout'])->name('logout');

    // EMPRESAS
    Route::prefix('empresas')->group(function () {
        Route::get('cadastrar', [EmpresaController::class, 'create'])->name('empresas.create');
        Route::post('cadastrar', [EmpresaController::class, 'store'])->name('empresas.store');
        Route::get('listar', [EmpresaController::class, 'index'])->name('empresas.index');
        Route::get('{id}/editar', [EmpresaController::class, 'edit'])->name('empresas.edit');
        Route::put('{id}', [EmpresaController::class, 'update'])->name('empresas.update');
        Route::delete('{id}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');
    });

    // FUNCIONÁRIOS
    Route::prefix('funcionarios')->group(function () {
Route::get('cadastrar', [FuncionarioController::class, 'create'])->name('funcionarios.create');
Route::post('cadastrar', [FuncionarioController::class, 'store'])->name('funcionarios.store');
Route::get('listar', [FuncionarioController::class, 'index'])->name('funcionarios.index');
Route::get('{id}/editar', [FuncionarioController::class, 'edit'])->name('funcionarios.edit');
Route::put('{id}', [FuncionarioController::class, 'update'])->name('funcionarios.update');
Route::delete('{id}', [FuncionarioController::class, 'destroy'])->name('funcionarios.destroy');
});
*/

    // CARGOS
    // Route::prefix('cargos')->group(function () {
    //     Route::get('cadastrar', [CargoController::class, 'create'])->name('cargos.create');
    //     Route::post('cadastrar', [CargoController::class, 'store'])->name('cargos.store');
    //     Route::get('listar', [CargoController::class, 'index'])->name('cargos.index');
    // });

    // TIPOS DE RISCO
    // Route::prefix('tipos-de-risco')->group(function () {
    //     Route::get('cadastrar', [TipoDeRiscoController::class, 'create'])->name('tipos_risco.create');
    //     Route::post('cadastrar', [TipoDeRiscoController::class, 'store'])->name('tipos_risco.store');
    //     Route::get('listar', [TipoDeRiscoController::class, 'index'])->name('tipos_risco.index');
    // });

    // RISCOS
    // Route::prefix('riscos')->group(function () {
    //     Route::get('cadastrar', [RiscoController::class, 'create'])->name('riscos.create');
    //     Route::post('cadastrar', [RiscoController::class, 'store'])->name('riscos.store');
    //     Route::get('listar', [RiscoController::class, 'index'])->name('riscos.index');
    // });

    // ===== Encaminhamentos (CRUD REST) =====
    // Route::resource('encaminhamentos', EncaminhamentoController::class);

    // FORMULÁRIOS EXTRAS (se desejar manter)
    // Route::prefix('forms')->group(function(){
    //     //Route::get('cadastrar-funcionario', fn() => view('pages.forms.cadastrar-funcionario'));
    //     //Route::get('listar-funcionario', fn() => view('pages.forms.listar-funcionario'));
    //     //Route::get('cadastrar-empresa', fn() => view('pages.forms.cadastrar-empresa'));
    //     //Route::get('listar-empresa', fn() => view('pages.forms.listar-empresa'));
    //     Route::get('gerar-exame', fn() => view('pages.forms.gerar-exame'));
    //     // EMPRESAS
    //     Route::get('listar-empresa',   [EmpresaController::class, 'index'])->name('empresas.index');
    //     Route::get('cadastrar-empresa',[EmpresaController::class, 'create'])->name('empresas.create');
    //     Route::post('cadastrar-empresa',[EmpresaController::class, 'store'])->name('empresas.store');

    //     // FUNCIONÁRIOS
    //     Route::get('listar-funcionario',   [FuncionarioController::class, 'index'])->name('funcionarios.index');
    //     Route::get('cadastrar-funcionario',[FuncionarioController::class, 'create'])->name('funcionarios.create');
    //     Route::post('cadastrar-funcionario',[FuncionarioController::class, 'store'])->name('funcionarios.store');
    //     

    //     // agora usando controller (clean & testável)
    //     Route::get('listar-exames', [ListarExamesController::class, 'index'])
    //         ->name('forms.exames.index');
    //     
    //     //Route::get('gerar-exame',[FuncionarioController::class, 'create'])->name('gerar-exame.create');

    //     // **Gerar Exame**: usa o create do EncaminhamentoController
    //     Route::get('gerar-exame', [EncaminhamentoController::class, 'create'])
    //         ->name('encaminhamentos.create');

    //     // Listagem/relatório de exames (mantido)
    //     Route::get('listar-exames', [ListarExamesController::class, 'index'])
    //         ->name('forms.exames.index');
    //     
    //     // Impressão de encaminhamento
    //     Route::get('imprimir-exame/{id}', [ListarExamesController::class, 'imprimir'])
    //         ->name('forms.exames.imprimir');
    // });

    // GRÁFICOS EXEMPLOS
    // Route::prefix('charts')->group(function () {
    //     Route::get('apex', fn() => view('pages.charts.apex'));
    //     Route::get('chartjs', fn() => view('pages.charts.chartjs'));
    // });

    // PAGINAS ICONES
    // Route::group(['prefix' => 'icons'], function(){
    //     Route::get('lucide-icons', function () { return view('pages.icons.lucide-icons'); });
    //     Route::get('flag-icons', function () { return view('pages.icons.flag-icons'); });
    //     Route::get('mdi-icons', function () { return view('pages.icons.mdi-icons'); });
    // });

    // PAGINA PERFIL USUARIO
    // Route::group(['prefix' => 'general'], function(){
    //     Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    //     Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    //     Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    //     Route::post('profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
    //     Route::delete('profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
    // });

    // (adicione aqui outras rotas protegidas se quiser)
//});

////////////////////////////////////////
// ROTAS DE ERRO E UTILITÁRIAS
////////////////////////////////////////
Route::group(['prefix' => 'error'], function(){
    Route::get('404', fn() => view('pages.error.404'));
    Route::get('500', fn() => view('pages.error.500'));
});

// LIMPAR CACHE VIA WEB (use com cuidado)
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// ROTA DE TESTE SMTP (remover em produção)
/*Route::get('/teste-smtp', function () {
    \Illuminate\Support\Facades\Mail::raw('Teste SMTP MailerSend', function ($m) {
        $m->to(env('MAIL_ADMIN', config('mail.from.address')))
          ->subject('Teste SMTP');
    });
    return 'Email de teste enviado!';
});*/

/*
// CATCH-ALL → Página 404 para rotas inválidas
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');
*/



/*
Route::get('/', function () {
    return view('dashboard');
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

Route::get('/', [DashboardController::class, 'index']);

*/
use App\Http\Controllers\Auth\RegisterController;

Route::prefix('auth')->group(function () {
    Route::get('/', fn() => view('pages.auth.login'));
    Route::get('login', fn() => view('pages.auth.login'))->name('login');
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::get('forgot-password', fn() => view('pages.auth.forgot-password'))->name('password.request');
    Route::get('reset-password/{token}', fn($token) => view('pages.auth.reset-password', compact('token')))->name('password.reset');
    Route::get('verify-code', fn() => view('pages.auth.verify-code'));
    Route::get('confirm-codigo', fn() => view('pages.auth.confirm-codigo'));

    Route::post('login', function () {
        return response('Login desativado para testes.', 200);
    })->name('login.post')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    Route::post('register', [RegisterController::class, 'register'])->name('register.post')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    Route::post('forgot-password', function () {
        return response('Desativado', 200);
    })->name('password.email')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    Route::post('reset-password', function () {
        return response('Desativado', 200);
    })->name('password.update')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    Route::post('logout', function () {
        return redirect()->route('login')->with('success', 'Logout desativado para testes');
    })->name('logout')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    Route::get('{path}', function ($path) {
        $view = 'pages.auth.' . str_replace('/', '.', $path);
        return \Illuminate\Support\Facades\View::exists($view) ? view($view) : abort(404);
    })->where('path', '.*');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
// Páginas livres para navegação (sem proteção)
Route::get('/dashboard', fn() => view('dashboard'));
Route::get('/sample', fn() => view('sample'));

Route::get('/pages/forms/cadastrar-empresa', fn() => view('pages.forms.cadastrar-empresa'));
Route::get('/forms/cadastrar-empresa', [EmpresaController::class, 'create'])->name('empresas.create');
Route::get('/pages/forms/listar-empresa', fn() => view('pages.forms.listar-empresa'));
Route::get('/forms/listar-empresa', [EmpresaController::class, 'index'])->name('forms.empresas.index');
Route::get('/pages/forms/editar-empresa', fn() => view('pages.forms.editar-empresa'));

Route::get('/pages/forms/cadastrar-funcionario', fn() => view('pages.forms.cadastrar-funcionario'));
Route::get('/pages/forms/listar-funcionario', [FuncionarioController::class, 'index'])->name('funcionarios.index');
Route::get('/forms/listar-funcionario', [FuncionarioController::class, 'index'])->name('forms.funcionarios.index');
Route::get('/pages/forms/editar-funcionario', fn() => view('pages.forms.editar-funcionario'));

Route::get('/pages/forms/listar-exames', fn() => view('pages.forms.listar-exames'));
Route::get('/pages/forms/gerar-exame', fn() => view('pages.forms.gerar-exame'));
Route::get('/pages/forms/imprimir-encaminhamento', fn() => view('pages.forms.imprimir-encaminhamento'));

Route::get('/pages/general/profile', fn() => view('pages.general.profile'));

Route::get('/pages/icons/lucide-icons', fn() => view('pages.icons.lucide-icons'));
Route::get('/pages/icons/flag-icons', fn() => view('pages.icons.flag-icons'));
Route::get('/pages/icons/mdi-icons', fn() => view('pages.icons.mdi-icons'));

Route::get('/pages/error/404', fn() => view('pages.error.404'));
Route::get('/pages/error/500', fn() => view('pages.error.500'));

// Catch-all para qualquer view em resources/views/pages/*
Route::get('/pages/{path}', function ($path) {
    $view = 'pages.' . str_replace('/', '.', $path);
    return \Illuminate\Support\Facades\View::exists($view) ? view($view) : abort(404);
})->where('path', '.*');
// Rotas de empresas (sem proteção)
Route::get('/empresas/create', [EmpresaController::class, 'create'])->name('empresas.create');
Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
Route::get('/empresas/{id}/editar', [EmpresaController::class, 'edit'])->name('empresas.edit');
Route::delete('/empresas/{id}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');
Route::put('/empresas/{id}', [EmpresaController::class, 'update'])->name('empresas.update');

// Rotas de funcionários (sem proteção)
Route::get('/funcionarios/create', [FuncionarioController::class, 'create'])->name('funcionarios.create');
Route::post('/funcionarios', [FuncionarioController::class, 'store'])->name('funcionarios.store')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/funcionarios', [FuncionarioController::class, 'index'])->name('funcionarios.index');
Route::get('/funcionarios/{id}/editar', [FuncionarioController::class, 'edit'])->name('funcionarios.edit');
Route::put('/funcionarios/{id}', [FuncionarioController::class, 'update'])->name('funcionarios.update');
// Alias para URLs antigas de forms
Route::get('/forms/cadastrar-funcionario', [FuncionarioController::class, 'create'])->name('forms.funcionarios.create');
Route::post('/forms/exames/store', [EncaminhamentoController::class, 'store'])->name('forms.exames.store')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/forms/gerar-exame', [EncaminhamentoController::class, 'gerar'])->name('forms.exames.gerar');
Route::get('/forms/exames/listar', [EncaminhamentoController::class, 'index'])->name('forms.exames.index');
Route::get('/encaminhamentos/imprimir/{encaminhamentoId}', [EncaminhamentoController::class, 'imprimir'])->name('encaminhamentos.imprimir');

Route::resource('asos', AsoController::class);
Route::get('/asos/emitir/{encaminhamentoId}', [AsoController::class, 'create'])->name('asos.emitir');

// API para riscos → exames (sem proteção, para testes)
Route::post('/api/risco/exames', [RiscoExameController::class, 'examesPorRisco'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/api/risco/exames', [RiscoExameController::class, 'examesPorRisco']);
Route::get('/funcionarios/{id}/editar', [FuncionarioController::class, 'edit'])->name('funcionarios.edit');
Route::delete('/funcionarios/{id}', [FuncionarioController::class, 'destroy'])->name('funcionarios.destroy');
