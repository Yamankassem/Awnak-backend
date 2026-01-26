<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //This defines a custom renderer for exceptions.
        //It is the modern , correct way to handle exceptions in laravel 11+.
        $exceptions->render(function (Throwable $e, Request $request) {
            //First, if the request is not for an API or does not expect JSON,
            //Let Laravel is default HTML error handle it.
            if (!$request->expectsJson() && !$request->is('api/*')) {
                return null;
            }

            //IF a response is already prepared(e.g.,in a formrequest),return it directly.
            if ($e instanceof HttpResponseException) {
                return $e->getResponse();
            }

            //Log the detailed,private error for developers to debug.
            //This happens regardless of what is shown to the user.
            Log::error(
                "API Exception:" . get_class($e) . "- Message:" . $e->getMessage() . "- File:" . $e->getFile() . "-Line:" . $e->getLine()
            );

            //use a PHP 8 "match" expression for a clean, declarative way
            //to build response details based on the exception type.
            $responseDetails = match (get_class($e)) {
                ValidationException::class => [
                    'statusCode' => 422,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(), // Extract Validation errors
                ],
                AuthenticationException::class => [
                    'statusCode' => 401,
                    'message' => 'Unauthenticated',
                ],
                AuthorizationException::class => [
                    'statusCode' => 403,
                    'message' => 'This action is unauthorized',
                ],
                NotFoundHttpException::class => [
                    'statusCode' => 404,
                    'message' => 'The requested resource was not found',
                ],
                default => [
                    'statusCode' => $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500,
                    'message' => app()->isProduction()
                        ? 'An unexpected server error occured.'
                        : $e->getMessage(), //show detailed error only in development.
                ]
            };

            //Construct the final JSON response payload.
            $payload = [
                'status' => 'error',
                'message' => $responseDetails['message'],
            ];

            //Conditionally add the validation 'errors' array to the payload.
            if (!empty($responseDetails['errors'])) {
                $payload['errors'] = $responseDetails['errors'];
            }

            //Sanitize the status code to ensure it is valid HTTP error code (4xx-5xx).
            $statusCode = ($responseDetails['statusCode'] >= 400 && $responseDetails['statusCode'] < 600)
                ? $responseDetails['statusCode']
                : 500;

            //return the final ,formatted JSON response.
            return response()->json($payload, $statusCode);
        });
    })->create();
