<?php

namespace App\Exceptions;

use App\Http\Controllers\Uam\SysAdminOperation\LoginController as AdminLoginController;
use App\Http\Controllers\Uam\UserOperation\LoginController;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        logger(__METHOD__, $exception->guards());
        if (in_array('admin', $exception->guards())) {
            return redirect()
                ->guest(route(AdminLoginController::URL_ROUTE_NAME_INPUT_ACTION));
        }

        return redirect()
            ->guest($exception->redirectTo() ?? route(LoginController::URL_ROUTE_NAME_INPUT_ACTION));
    }

}
