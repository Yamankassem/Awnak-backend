<?php

namespace Modules\Applications\App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\AbstractPaginator;

class ApiResponse
{
    public static function success($message = 'messages.success', $data = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => __($message),
        ];

        // If it's paginated, restructure response
        if ($data instanceof AbstractPaginator) {
            $response['data'] = $data->items();
            $response['pagination'] = [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'links' => $data->linkCollection(),
            ];
        } else {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    public static function error(string $message = 'messages.default_error', $data = null, $errors = [], int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => __($message),
            'data' => $data,
            'errors' => $errors,
        ], $statusCode);
    }

    public static function validationError($errors = null): JsonResponse
    {
        return self::error('messages.validation_error', null, $errors, 422);
    }

    public static function forbidden($data = null): JsonResponse
    {
        return self::error('messages.forbidden', $data, null, 403);
    }

    public static function unauthenticated(): JsonResponse
    {
        return self::error('messages.unauthenticated', null, null, 401);
    }

    public static function localizedSuccess(string $key, $data = null, int $statusCode = 200): JsonResponse
    {
        return self::success(__("messages.$key"), $data, $statusCode);
    }

    public static function localizedError(string $key, $data = null, $errors = null, int $statusCode = 400): JsonResponse
    {
        return self::error("messages.$key", $data, $errors, $statusCode);
    }

    public static function unauthorized($message = 'messages.unauthorized'): JsonResponse
    {
        return self::error($message, null, null, 403);
    }

    public static function tooManyRequests($message = 'message.too_many_requests'): JsonResponse
    {
        return self::error($message, null, null, 429);
    }


    public static function notFound($message = 'message.not_found'): JsonResponse
    {
        return self::error($message, null, null, 404);
    }

    public static function created($data = null, $message = 'message.created_successfully'): JsonResponse
    {
        return self::success($message, $data, 201);
    }

    public static function updated($data = null, $message = 'message.updated_successfully'): JsonResponse
    {
        return self::success($message, $data);
    }

    public static function deleted($data = null, $message = 'message.deleted_successfully'): JsonResponse
    {
        return self::success($message, null);
    }
}
