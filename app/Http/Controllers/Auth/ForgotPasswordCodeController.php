<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Http\Requests\Auth\ResetPasswordCodeRequest;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordCodeController extends Controller
{
    // Formulário para pedir e-mail
    public function requestForm()
    {
        return view('auth.forgot-password-code');
    }

    // Envia o código por e-mail
    public function sendCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $code = mt_rand(100000, 999999);

        $expiresAt = now()->addMinutes(30);
        $url = route('password.code.form', ['email' => $user->email,  'code'  => $code,]);


        VerificationCode::create([
            'user_id' => $user->id,
            'type' => 'password_reset',
            'code_hash' => Hash::make($code),
            'expires_at'=> $expiresAt,
        ]);

        Mail::to($user->email)->send(new PasswordResetCodeMail(
            $user,
            $code,
            $url,
            now()->addMinutes(30)->format('d/m/Y'),
            now()->addMinutes(30)->format('H:i')
        ));

        return redirect()->route('password.code.form', ['email' => $user->email])
            ->with('success', 'Código enviado para o seu e-mail!');
    }

    // Formulário para digitar código
    public function verifyForm(Request $request)
    {
        $email = $request->query('email'); // pega da URL
        $code  = $request->query('code');  // pega da URL, se existir

        return view('auth.reset-password-code', compact('email', 'code'));
    }

    // Valida apenas o código (primeira etapa)
    public function validateCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        $resetCode = VerificationCode::where('user_id', $user->id)
            ->where('type', 'password_reset')
            ->latest()
            ->first();

        if (!$resetCode || $resetCode->isExpired() || !Hash::check($request->code, $resetCode->code_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Código inválido ou expirado.'
            ]);
        }

        return response()->json(['success' => true]);
    }

    // Reseta a senha (segunda etapa)
    public function resetPassword(ResetPasswordCodeRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        $resetCode = VerificationCode::where('user_id', $user->id)
            ->where('type', 'password_reset')
            ->latest()
            ->first();

        if (!$resetCode || $resetCode->isExpired() || !Hash::check($request->code, $resetCode->code_hash)) {
            return back()->with('error', 'Código inválido ou expirado.');
        }

        $user->update(['password' => Hash::make($request->password)]);
        $resetCode->delete();

        return redirect()->route('login')->with('success', 'Senha redefinida com sucesso!');
    }
}