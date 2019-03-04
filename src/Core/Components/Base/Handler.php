<?php

namespace src\Core\Compoents\Base;

use src\Core\Compoents\Controllers\Base\AppException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        $AppException = AppException::make($exception);
        $AppException->report();
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        $AppException = AppException::make($exception);
        return $AppException->render($request);
    }
}