<?php

namespace App\Service\Order\Exceptions;

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class ConectionFailedException extends \LogicException
{
    public function __construct($redirectionOrder)
    {
        parent::__construct(sprintf("No es posible hacer la conexion por favor valida tus credenciales.", $redirectionOrder->status, $redirectionOrder->code)));
    }

}