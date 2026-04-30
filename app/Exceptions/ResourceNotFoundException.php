<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

/**
 * Exception for resource not found scenarios
 */
class ResourceNotFoundException extends ApplicationException
{
    public function __construct(string $resource, $identifier)
    {
        $userMessage = "{$resource} tidak ditemukan.";
        $message = "{$resource} not found: {$identifier}";

        parent::__construct($message, $userMessage, Response::HTTP_NOT_FOUND);
    }
}
