<?php
namespace App\Http\Responses;

/**
 * Class ApiResponse
 * API
 *
 * @package App\Http
 */
class ApiResponse
{
    /**
     * Success Response
     * @param $data
     * @param string|null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, ?string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Error Response
     * @param array $errors
     * @param string|null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse(array $errors, ?string $message = null, int $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Validation Error Response
     * @param array $errors
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationErrorResponse(array $errors, ?string $message = 'Validation Failed')
    {
        return $this->errorResponse($errors, $message, 422);
    }

    /**
     * Not Found Response
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFoundResponse(?string $message = 'Resource Not Found')
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => []
        ], 404);
    }

    /**
     * Custom Response
     * @param $data
     * @param string $status
     * @param string|null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function customResponse($data, string $status, ?string $message = null, int $code)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
