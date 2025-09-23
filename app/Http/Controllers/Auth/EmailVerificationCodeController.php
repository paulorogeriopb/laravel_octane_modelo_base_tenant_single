<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationCodeMail;

class EmailVerificationCodeController extends Controller
{
    /**
     * Exibe o formulário de solicitação do código
     */
    public function showRequestForm()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended();
        }

        return view('auth.request-verification');
    }

    /**
     * Envia o código de verificação por e-mail
     */
    public function sendVerificationCode(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Limpa códigos antigos de verificação de e-mail
        VerificationCode::where('user_id', $user->id)
            ->where('type', 'email_verification')
            ->delete();

        // Gera código aleatório
        $code = mt_rand(100000, 999999);

       // Define expiração e url
        $expiresAt = now()->addMinutes(60);
       $url = route('email-verification.form') . '?email=' . urlencode($user->email) . '&code=' . $code;

        // Salva código hash no banco
        VerificationCode::create([
            'user_id' => $user->id,
            'type' => 'email_verification', // IMPORTANTE: define o tipo
            'code_hash' => Hash::make($code),
            'expires_at'=> $expiresAt,

        ]);

        \Log::info('Código gerado para verificação', ['user_id' => $user->id, 'code' => $code]);

        // Envia e-mail
        Mail::to($user->email)->send(new EmailVerificationCodeMail(
            $user,
            $code,
            now()->addMinutes(60)->format('d/m/Y'),
            now()->addMinutes(60)->format('H:i'),
            $url,
        ));

        return redirect()->route('email-verification.form')
            ->with('success', 'Código de verificação enviado para seu e-mail!');
    }

    /**
     * Exibe o formulário para digitar o código
     */
    public function showVerificationForm(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

         return view('auth.verify-email', [
            'email' => $request->query('email'),
            'code'  => $request->query('code'),
        ]);
    }

    /**
     * Valida o código digitado pelo usuário
     */
    public function verifyCode(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Você precisa estar logado para verificar o código.']);
        }

        $request->validate([
            'code' => 'required|string',
        ]);

        $record = VerificationCode::where('user_id', $user->id)
            ->where('type', 'email_verification')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$record || !Hash::check($request->code, $record->code_hash)) {
            \Log::warning('Código inválido ou expirado', [
                'user_id' => $user->id,
                'input_code' => $request->code,
                'stored_hash' => $record?->code_hash,
            ]);

            return back()->withErrors(['code' => 'Código inválido ou expirado.']);
        }

        // Marca e-mail como verificado e remove o registro
        $user->markEmailAsVerified();
        $record->delete();

        return redirect()->route('dashboard')
            ->with('success', 'E-mail verificado com sucesso!');
    }
}