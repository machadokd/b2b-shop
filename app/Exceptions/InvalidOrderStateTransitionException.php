<?php

namespace App\Exceptions;

use App\Enums\OrderStatus;
use RuntimeException;

class InvalidOrderStateTransitionException extends RuntimeException
{
    public function __construct(OrderStatus $from, OrderStatus $to)
    {
        parent::__construct(
            "Transição inválida de '{$from->label()}' para '{$to->label()}'."
        );
    }
}
