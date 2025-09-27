<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    public function report(Throwable $exception): void
    {
        $this->logError($exception);
        parent::report($exception);
    }

    /**
     * Log global de erros.
     *
     * @param \Throwable $exception
     * @param \Illuminate\Http\Request|null $request
     */
    private function logError(Throwable $exception, ?Request $request = null): void
    {
        $user = Auth::user();

        $context = [
            'user_id'     => $user?->id,
            'user_email'  => $user?->email,
            'ip'          => $request?->ip() ?? request()->ip(),
            'user_agent'  => $request?->userAgent() ?? request()->userAgent(),
            'url'         => $request?->fullUrl() ?? request()->fullUrl(),
            'route'       => $request?->route()?->getName() ?? request()->route()?->getName(),
            'exception'   => $exception,
        ];

        Log::error($exception->getMessage(), $context);
    }

    public function render($request, Throwable $exception)
    {
        // Se APP_DEBUG=true, exibe detalhes do erro
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        // Caso contrário, mostra uma página genérica
        return response()->view('errors.500', ['message' => 'Ocorreu um erro no servidor.'], 500);
    }
}