<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        
        if ($e instanceof UnauthorizedHttpException) {
            return response()->json(
            [
                'status_code' => 401,
                'data' => [
                    'error' => 401,
                    'message' => "Unauthenticated - USER TOKEN NOT PROVIDED",
                ]
            ], 401);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json( [
                'status_code' => 405,
                'data' => [
                    'error' => 405,
                    'message' => 'Method is not allowed for the requested route',
                ]
            ], 405 );
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(
            [
                'success' => false,
                'data' => [
                    'error' => 401,
                    'message' => "Unauthenticated",
                ]
            ], 401);
        }

        return redirect()->guest(route('login'));
    }
}
