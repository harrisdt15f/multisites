<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldntReport($exception)) {
            return;
        }
        Log::channel('daily')->error(
            $exception->getMessage(),
            array_merge($this->context(), ['exception' => $exception])
        );
//        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            $msg = $exception->getMessage();
            if ($msg = 'Unauthenticated.') {
                $result = [
                    'success' => false,
                    'code' => $exception->getCode(),
                    'data' => [],
                    'message' => '您没有权限操作 请尝试先登录',
                ];
            } else {
                $result = ['message' => $msg];
            }
            return response()->json($result, 200);
        } else {
            return redirect()->guest($exception->redirectTo() ?? route('login'));
        }
    }
}
