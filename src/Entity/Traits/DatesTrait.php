<?php

namespace App\Entity\Traits;

use App\Entity\Embedded\Date;
use Doctrine\ORM\Mapping as ORM;

trait DatesTrait
{
    #[ORM\Embedded(class: Date::class, columnPrefix: false)]
    protected Date $dates;

    public function getDates(): Date
    {
        return $this->dates ??= new Date();
    }
}