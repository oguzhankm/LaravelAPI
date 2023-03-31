<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ResultType;
use http\Env\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Exception;
use Mockery\Matcher\Not;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     * @param \Request
     * @return JsonResponse
     */
    public function register()
    {

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                $previous =  $e->getPrevious();
                if ($previous instanceof ModelNotFoundException) {
                    $model = $previous->getModel();
                    return response()->json([
                        'status' => 204,
                        'message' => last(explode('\\', $model)) . ' not found'
                    ], 200);
                }
                return response()->json([
                    'status' => 404,
                    'message' => 'Page not found'
                ], 404);
            }
        });
    }




//    public function render($request, Exception $exception)
//    {
//        if ($exception instanceof ModelNotFoundException)
//            return (new ApiController)->apiResponse(ResultType::Error, null, 'Validation error!', JsonResponse::HTTP_NOT_FOUND);
//
//            return parent::render($request, $exception);
//
//
//    }
}
