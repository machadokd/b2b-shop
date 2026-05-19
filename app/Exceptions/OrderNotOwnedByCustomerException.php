<?php

namespace App\Exceptions;

use RuntimeException;

class OrderNotOwnedByCustomerException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Acesso negado: encomenda não pertence ao cliente autenticado.');
    }
}
