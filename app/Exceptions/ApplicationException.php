<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Application-level exception class
 * Use for business logic errors
 */
class ApplicationException extends Exception
{
    protected string $userMessage;

    protected int $statusCode;

    public function __construct(string $message, ?string $userMessage = null, int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message);

        $this->userMessage = $userMessage ?? $message;
        $this->statusCode = $statusCode;
    }

    /**
     * Get the user-friendly error message
     */
    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    /**
     * Get the HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Render the exception as a response
     */
    public function render(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getUserMessage(),
                'error' => $this->getMessage(),
            ], $this->getStatusCode());
        }

        return response()->view('errors.application', [
            'message' => $this->getUserMessage(),
            'error' => $this->getMessage(),
        ], $this->getStatusCode());
    }
}
