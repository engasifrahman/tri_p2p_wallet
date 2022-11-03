<?php

namespace App\Exceptions;

use Throwable;
use BadMethodCallException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if($request->wantsJson()){
                $errorMessage = $e->getMessage();

                response()->setResponse(false, 'You are unauthenticated', null, null, 401);
                return response()->getResponse();
            }
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if($request->wantsJson()){
                $errorMessage = $e->getMessage();
                $statusCode = $e->getStatusCode();

                response()->setResponse(false, $errorMessage ? $errorMessage : 'You are not authorized', null, null, $statusCode);
                return response()->getResponse();
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if($request->wantsJson()){
                $message = 'Not found';

                $errorMessage = $e->getMessage();
                $statusCode = $e->getStatusCode();

                if($errorMessage && strripos($errorMessage, 'No query results for model') != -1){
                    $message = 'Requested Object not found';
                }

                response()->setResponse(false, $message, null, null, $statusCode);
                return response()->getResponse();
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if($request->wantsJson()){
                $errorMessage = $e->getMessage();
                $statusCode = $e->getStatusCode();

                response()->setResponse(false, 'The method is not supported for this route', null, null,  $statusCode);
                return response()->getResponse();
            }
        });

        $this->renderable(function (QueryException $e, $request) {
            if($request->wantsJson()){
                $errorMessage = $e->getMessage();

                response()->setResponse(false, 'Query exception', null, null, 500);
                return response()->getResponse();
            }
        });

        $this->renderable(function (BadMethodCallException $e, $request) {
            if($request->wantsJson()){
                $errorMessage = $e->getMessage();

                response()->setResponse(false, 'Server error', null, null, 500);
                return response()->getResponse();
            }
        });

        $this->renderable(function (HttpException $e, $request) {
            if($request->wantsJson()){
                $errorMessage = $e->getMessage();
                $statusCode = $e->getStatusCode();

                response()->setResponse(false, $errorMessage, null, null,  $statusCode);
                return response()->getResponse();
            }
        });
    }
}
