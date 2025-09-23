<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $loginRequest): RedirectResponse
    {
        $loginRequest->authenticate();

        $user = Auth::user();

        // ⚠️ Bloqueia login se status do usuário não for "Ativo" (id = 1)
        if ($user->user_status_id !== 1) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('auth.status_inactive'),
            ]);
        }

        $loginRequest->session()->regenerate();

        // Mata sessão anterior, se existir
        if ($user->last_session_id && $user->last_session_id !== Session::getId()) {
            DB::table('sessions')->where('id', $user->last_session_id)->delete();
        }

        // Salva nova session_id
        $user->last_session_id = Session::getId();
        $user->save();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
