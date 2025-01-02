<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof MethodNotAllowedHttpException) {
            return responder()->method_not_allowed();
        } else if ($e instanceof ValidationException) {
            $errors = $e->errors();
            return responder()->respond(1, 'Invalid request data', $errors, null, [], 400);
        } else if ($e instanceof NotFoundHttpException) {
            return responder()->not_found();
        } else if ($e instanceof AuthenticationException) {
            return responder()->unauthorized();
        } else if ($e instanceof AuthorizationException) {
            return responder()->forbidden();
        }
        Log::error($e);
        return parent::render($request, $e);
    }


}
