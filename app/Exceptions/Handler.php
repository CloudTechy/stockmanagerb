<?php

namespace App\Exceptions;

use App\Helper;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        /*\Illuminate\Auth\AuthenticationException::class,
    \Illuminate\\Database\\QueryException
    \Symfony\\Component\\HttpKernel\\Exception\\MethodNotAllowedHttpException:class
    \Illuminate\Auth\Access\AuthorizationException::class,
    \Symfony\Component\HttpKernel\Exception\HttpException::class,
    \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    \Illuminate\Validation\ValidationException::class,
    \Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException::class,*/
    ];
    protected function exception(Exception $bug, $message, $code = 500)
    {

        if ($code == 500) {
            $message = "Oops. An unexpected error occured";
        }

        $error = $bug->getMessage();

        return Helper::invalidRequest($error, $message, $code);
    }

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
    public function render($request, Exception $exception)
    {

        if ($exception instanceof ModelNotFoundException) {
            return $this->exception($exception, str_replace('App\\', '', $exception->getModel()) . ' not found', 400);

        } /* elseif ($exception instanceof FatalThrowableError) {
        return $this->exception($exception, 'unknown error', 500);
        } */elseif ($exception instanceof ValidationException) {

            $errors = $exception->errors();
            $message = "Bad Request! Request Parameter(s) Failed Validation";
            return Helper::invalidRequest($errors, $message, 422);

        } elseif ($exception instanceof AuthenticationException) {

            $message = "User authentication Failure";

            return $this->exception($exception, $message, 401);

        }
        return parent::render($request, $exception);
    }
}
