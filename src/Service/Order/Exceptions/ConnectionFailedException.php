<?php

namespace App\Service\Order\Exceptions;

use Dnetix\Redirection\Message\RedirectResponse;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class ConnectionFailedException extends \LogicException
{
    public function __construct(RedirectResponse $redirectionOrder)
    {
        parent::__construct(sprintf("Ups! Error (%s) No es posible hacer la conexion por favor valida tus credenciales.", $redirectionOrder->status()->message()));
    }

}