<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

/**
 * Exception for insufficient stock scenarios
 */
class InsufficientStockException extends ApplicationException
{
    public function __construct(string $productName, int $available, int $requested)
    {
        $userMessage = "Stok {$productName} tidak cukup. Tersedia: {$available}, diminta: {$requested}";
        $message = "Insufficient stock for {$productName}: available={$available}, requested={$requested}";

        parent::__construct($message, $userMessage, Response::HTTP_CONFLICT);
    }
}
