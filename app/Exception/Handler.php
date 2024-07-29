<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;


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
    
    //customize the 404 page
//    public function render($request, Throwable $exception)
//     {
//         if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
//             return response()->view('errors.404', [], 404);
//         }

//         return parent::render($request, $exception);
    
//     }
     public function render($request, Throwable $exception)
     {
         if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
             return response()->view('errors.404', [], 404);
         } elseif ($exception instanceof AuthenticationException) {
             return response()->view('errors.401', [], 401);
         } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() === 419) {
             return response()->view('errors.419', [], 419);
         }  elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() === 403) {
             return response()->view('errors.403', [], 403);
         }

    
         return parent::render($request, $exception);
     }
    

}
