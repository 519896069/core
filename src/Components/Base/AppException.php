<?php

namespace Core\Components\Controllers\Base;

use Core\Core;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppException extends Exception
{
    private   $exception    = null;
    private   $system_error = false;
    protected $code;
    protected $message;

    public function __construct($code = 0, $message = 'system error', Exception $exception = null, $system_error = false)
    {
        $this->exception    = $exception;
        $this->system_error = $system_error;

        $this->code    = $code;
        $this->message = $message;
    }

    public static function make(Exception $exception)
    {
        return new static(null, null, $exception);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function render($request)
    {
        DB::rollBack();
        $http_code = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($this->exception instanceof HttpException)
            $http_code = $this->exception->getStatusCode();
        if ($this->exception instanceof NotFoundHttpException)
            $http_code = Response::HTTP_NOT_FOUND;
        if ($this->exception instanceof AppException)
            $http_code = Response::HTTP_BAD_REQUEST;
        $data = [
            'error_code' => $this->exception->getCode(),
            'error_msg'  => $this->exception->getMessage(),
        ];
        if (config('app.debug')) {
            $errorData['meta']['debug'] = [
                'message' => $this->exception->getMessage(),
                'line'    => $this->exception->getLine(),
                'files'   => $this->exception->getFile(),
                'trace'   => $this->exception->getTraceAsString(),
            ];
        }
        return response()->json($data, $http_code, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     *
     */
    public function report()
    {
        $router = request()->route();
        if ($router) {
            $title = "API " . $router->getName();
        } else {
            $title = "Command ";
        }
        if (!($this->exception instanceof AppException)) {
            Core::printLog("$title ERROR:", [
                'message' => $this->exception->getMessage(),
                'line'    => $this->exception->getLine(),
                'files'   => $this->exception->getFile(),
                'trace'   => $this->exception->getTraceAsString(),
            ], Core::ERROR);
        }
    }
}