<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Mail\EmailVerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Exibe o formulário de registro
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Cria o usuário, gera código de verificação e envia e-mail
     */
    public function store(Request $request)
    {
        // Validação do formulário
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Cria o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Atribui papel 'User', se existir
        if (\Spatie\Permission\Models\Role::where('name', 'User')->exists()) {
            $user->assignRole('User');
        }

        // Gera código de verificação
        $code = mt_rand(100000, 999999);
        VerificationCode::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(60),
        ]);

        // Envia e-mail de verificação
        Mail::to($user->email)->send(new EmailVerificationCodeMail(
            $user,
            $code,
            now()->addMinutes(60)->format('d/m/Y'),
            now()->addMinutes(60)->format('H:i'),
            route('email-verification.form')
        ));

        // Loga o usuário
        Auth::login($user);

        // Redireciona para a tela de verificação
        return redirect()->route('email-verification.form')
            ->with('success', 'Conta criada com sucesso! Código enviado para seu e-mail.');
    }
}
