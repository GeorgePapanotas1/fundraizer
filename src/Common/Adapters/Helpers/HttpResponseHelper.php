<?php

declare(strict_types=1);

namespace Fundraiser\Common\Adapters\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;

class HttpResponseHelper
{
    /**
     * Return a success response.
     *
     * @param  array<string, string>|null  $links
     */
    public static function success(mixed $data = null, ?array $links = null, int $status = 200, string $message = 'Success'): JsonResponse
    {
        $response = array_filter([
            'message' => $message,
            'data' => $data,
            '_links' => $links,
        ], fn ($value) => ! is_null($value));

        return response()->json($response, $status);
    }

    /**
     * Return an error response.
     *
     * @param  array<string, string>  $errors
     */
    public static function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    public static function serverError(): JsonResponse
    {
        return static::error(__('Something went wrong'), 500);
    }

    /**
     * Handle an exception and return a JSON response.
     */
    public static function exception(Exception $exception, int $status = 500): JsonResponse
    {
        return response()->json([
            'message' => $exception->getMessage(),
            'trace' => config('app.debug') ? $exception->getTrace() : null,
        ], $status);
    }
}
