<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\View\ViewException;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    public function report(Throwable $throwable): void
    {
        parent::report($throwable);
    }

    public function render($request, Throwable $throwable)
    {
        // Se for erro de view (ex: erro de Blade)
        if ($throwable instanceof ViewException) {
            $message = $throwable->getMessage();

            // Logar apenas a mensagem do erro sem o stacktrace
            Log::channel('view')->error($message);

            // Opcional: mostrar uma página de erro genérica ou personalizada
            return response()->view('errors.view', ['message' => $message], 500);
        }

        return parent::render($request, $throwable);
    }
}
