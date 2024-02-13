<?php

namespace App\Exceptions;

use App\Utilities\InternalResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
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

    /**
     * Render an exception into an HTTP response.
     * @param $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, $exception)
    {
        $internalError = new InternalResponse();
        if ($exception instanceof AuthorizationException) {
            $res = $internalError->respondUnauthorizedError('You can not access to this resource');
            return response()->json($res, $res['status_code']);
        } else if ($exception instanceof ModelNotFoundException) {
            $res = $internalError->respondNotFound();
            return response()->json($res, $res['status_code']);
        };
        return parent::render($request, $exception);
    }
}
