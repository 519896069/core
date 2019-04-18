<?php

namespace Core\Components\Controllers\Base;

use Core\Core;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppException extends HttpException
{
    private $system_error = false;

    /**
     * AppException constructor.
     * @param int $code
     * @param string $message
     * @param Exception|null $exception
     * @param bool $system_error
     * @param array $headers
     */
    public function __construct($code = 0,
                                $message = 'system error',
                                Exception $exception = null,
                                $system_error = false,
                                $headers = []
    )
    {
        parent::__construct(
            method_exists($exception, 'getStatusCode')
                ? $exception->getStatusCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR,
            $message,
            $exception,
            $headers,
            $code
        );
        $this->system_error = $system_error;
    }

    public static function make(Exception $exception)
    {
        return new static($exception->getCode(), $exception->getMessage(), $exception);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        DB::rollBack();
        $data = [
            'error_code' => $this->getPrevious()->getCode(),
            'error_msg'  => $this->getPrevious()->getMessage(),
        ];
        if (config('app.debug')) {
            $errorData['meta']['debug'] = [
                'message' => $this->getPrevious()->getMessage(),
                'line'    => $this->getPrevious()->getLine(),
                'files'   => $this->getPrevious()->getFile(),
                'trace'   => $this->getPrevious()->getTraceAsString(),
            ];
        }
        return response()->json($data, $this->getStatusCode(), [], JSON_UNESCAPED_UNICODE);
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
        if (!($this->getPrevious() instanceof AppException)) {
            Core::printLog("$title ERROR:", [
                'message' => $this->getPrevious()->getMessage(),
                'line'    => $this->getPrevious()->getLine(),
                'files'   => $this->getPrevious()->getFile(),
                'trace'   => $this->getPrevious()->getTraceAsString(),
            ], Core::ERROR);
        }
    }
}