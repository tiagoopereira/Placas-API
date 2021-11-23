<?php

namespace App\Exception;

class ValidationException extends \Exception
{
    public function __construct(string $message = "")
    {
        $message = empty($message) ? "Erro ao criar cliente" : $message;

        parent::__construct($message);
    } 
}