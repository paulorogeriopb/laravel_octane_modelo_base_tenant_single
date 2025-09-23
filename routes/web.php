<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\Auth\EmailVerificationCodeController;
use App\Http\Controllers\Auth\ForgotPasswordCodeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Rotas públicas
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));
Route::view('/offline', 'offline');


// Recuperação de senha por código
Route::middleware('guest')->group(function () {
    Route::get('forgot-password-code', [ForgotPasswordCodeController::class, 'requestForm'])
        ->name('password.code.request');
    Route::post('forgot-password-code', [ForgotPasswordCodeController::class, 'sendCode'])
        ->name('password.code.send');
    Route::get('reset-password-code', [ForgotPasswordCodeController::class, 'verifyForm'])
        ->name('password.code.form');

    // Validação do código (POST)
    Route::post('validate-code', [ForgotPasswordCodeController::class, 'validateCode'])
        ->name('password.code.validate');

    // Reset de senha
    Route::post('reset-password-code', [ForgotPasswordCodeController::class, 'resetPassword'])
        ->name('password.code.reset');
});


/*
|--------------------------------------------------------------------------
| Rotas para convidados (guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Registro e login
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);



});

/*
|--------------------------------------------------------------------------
| Rotas para usuários autenticados (mesmo sem verificação de e-mail)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Solicitação do código
    Route::get('email/verification', [EmailVerificationCodeController::class, 'showRequestForm'])
        ->name('email-verification.request');
    Route::post('email/verification/send', [EmailVerificationCodeController::class, 'sendVerificationCode'])
        ->name('email-verification.send');

    // Formulário para digitar o código
    Route::get('email/verify-code', [EmailVerificationCodeController::class, 'showVerificationForm'])
        ->name('email-verification.form');
    Route::post('email/verify-code', [EmailVerificationCodeController::class, 'verifyCode'])
        ->name('email-verification.verify');
});

/*
|--------------------------------------------------------------------------
| Rotas autenticadas e com e-mail verificado
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Auditoria
    Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');

    // Debug de idioma
    Route::get('/debug-locale', fn() => [
        'session_locale' => session('locale'),
        'app_locale' => app()->getLocale(),
    ]);
    Route::post('/change-language', function (Request $request) {
        session()->put('locale', $request->get('locale'));
        return back();
    })->name('change-language');

    /*
    |--------------------------------------------------------------------------
    | Traduções
    |--------------------------------------------------------------------------
    */
    Route::prefix('translations')->group(function () {
        Route::get('/', [TranslationController::class, 'index'])->name('translations.index')->middleware('permission:translation-index');
        Route::get('/create', [TranslationController::class, 'create'])->name('translations.create')->middleware('permission:translation-create');
        Route::post('/', [TranslationController::class, 'store'])->name('translations.store')->middleware('permission:translation-create');
        Route::get('/{translation}/edit', [TranslationController::class, 'edit'])->name('translations.edit')->middleware('permission:translation-edit');
        Route::put('/{translation}', [TranslationController::class, 'update'])->name('translations.update')->middleware('permission:translation-edit');
        Route::delete('/{translation}', [TranslationController::class, 'destroy'])->name('translations.destroy')->middleware('permission:translation-destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Usuários e Senhas
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index')->middleware('permission:user-index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:user-create');
        Route::post('/', [UserController::class, 'store'])->name('users.store')->middleware('permission:user-create');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:user-show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:user-edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:user-edit');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:user-destroy');

        Route::get('/{user}/edit-password', [UserController::class, 'editPassword'])->name('users.edit_password')->middleware('permission:users-edit-password');
        Route::put('/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update_password')->middleware('permission:users-edit-password');
        Route::patch('/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus')->middleware('permission:user-status-edit');
    });

    /*
    |--------------------------------------------------------------------------
    | Status de Usuários
    |--------------------------------------------------------------------------
    */
    Route::prefix('user-statuses')->group(function () {
        Route::get('/', [UserStatusController::class, 'index'])->name('user_statuses.index')->middleware('permission:user-status-index');
        Route::get('/create', [UserStatusController::class, 'create'])->name('user_statuses.create')->middleware('permission:user-status-create');
        Route::post('/', [UserStatusController::class, 'store'])->name('user_statuses.store')->middleware('permission:user-status-create');
        Route::get('/{userStatus}/edit', [UserStatusController::class, 'edit'])->name('user_statuses.edit')->middleware('permission:user-status-edit');
        Route::put('/{userStatus}', [UserStatusController::class, 'update'])->name('user_statuses.update')->middleware('permission:user-status-edit');
        Route::delete('/{userStatus}', [UserStatusController::class, 'destroy'])->name('user_statuses.destroy')->middleware('permission:user-status-destroy');
        Route::get('/{userStatus}', [UserStatusController::class, 'show'])->name('user_statuses.show')->middleware('permission:user-status-show');
    });

    /*
    |--------------------------------------------------------------------------
    | Papéis e Permissões
    |--------------------------------------------------------------------------
    */
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:role-index');
        Route::get('/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:role-create');
        Route::post('/', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:role-create');
        Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:role-show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:role-edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:role-edit');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:role-destroy');

        Route::get('/permissions/{role}', [RolePermissionController::class, 'index'])->name('role-permissions.index')->middleware('permission:permission-role-index');
        Route::patch('/permissions/{role}/{permission}', [RolePermissionController::class, 'update'])->name('role-permissions.update')->middleware('permission:permission-role-update');
        Route::patch('/permissions/{role}/toggle-user/{user}', [RolePermissionController::class, 'toggleUser'])->name('role-permissions.toggleUser')->middleware('permission:permission-role-update');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:permission-index');
        Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:permission-create');
        Route::post('/', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:permission-create');
        Route::get('/{permission}', [PermissionController::class, 'show'])->name('permissions.show')->middleware('permission:permission-show');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:permission-edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:permission-edit');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:permission-destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Cursos
    |--------------------------------------------------------------------------
    */
    Route::prefix('cursos')->group(function () {
        Route::get('/', [CursosController::class, 'index'])->name('cursos.index')->middleware('permission:cursos-index');
        Route::get('/create', [CursosController::class, 'create'])->name('cursos.create')->middleware('permission:cursos-create');
        Route::post('/', [CursosController::class, 'store'])->name('cursos.store')->middleware('permission:cursos-create');
        Route::get('/{curso}/edit', [CursosController::class, 'edit'])->name('cursos.edit')->middleware('permission:cursos-edit');
        Route::put('/{curso}', [CursosController::class, 'update'])->name('cursos.update')->middleware('permission:cursos-edit');
        Route::delete('/{curso}', [CursosController::class, 'destroy'])->name('cursos.destroy')->middleware('permission:cursos-destroy');
    });
});

// Rotas do auth padrão
require __DIR__.'/auth.php';