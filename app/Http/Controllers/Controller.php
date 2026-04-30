<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Standardized success response
     */
    protected function successResponse($data = null, string $message = 'Operasi berhasil', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Standardized error response
     */
    protected function errorResponse(string $message = 'Terjadi kesalahan', $errors = null, int $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Standardized redirect with success message
     */
    protected function redirectSuccess($route, ?string $message = null, ?array $parameters = [])
    {
        return redirect()->route($route, $parameters)
            ->with('success', $message ?? 'Operasi berhasil');
    }

    /**
     * Standardized redirect with error message
     */
    protected function redirectError($route, string $message, ?array $parameters = [])
    {
        return redirect()->route($route, $parameters)
            ->withErrors(['error' => $message])
            ->withInput();
    }
}
