<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
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
        if ($e instanceof TokenMismatchException) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Session expired. Please login again.'
                ], 419);
            }

            return redirect()
                ->route('login')
                ->with('message', 'Your session has expired. Please login again.');
        }

        return parent::render($request, $e);
    }
}
