<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            Log::info($exception);

            return response()->json(['success' => false, 'message' => 'Ocorreu um erro interno. Contate o adminnistrador do sistema'], 500);
        }
        if ($exception instanceof ValidationException) {
            Log::info($exception);

            return response()->json(['success' => false, 'message' => $exception->validator->errors()->first()], 409);
        }
        if ($exception instanceof AuthenticationException) {
            Log::info($exception);

            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }
        if ($exception instanceof MissingAbilityException) {
            Log::info($exception);

            return response()->json(['success' => false, 'message' => 'Você não tem permissão para realizar essa ação'], 401);
        }
        Log::info($exception);

        return response()->json(['success' => false, 'message' => 'Ocorreu um erro interno. Contate o adminnistrador do sistema'], 500);
    }
}
