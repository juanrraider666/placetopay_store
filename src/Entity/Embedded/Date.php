<?php

namespace App\Entity\Embedded;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Date
{
    #[ORM\Column(
        name: 'created_at',
        updatable: false,
        generated: 'INSERT',
    )]
    protected DateTimeImmutable $created;

    #[ORM\Column(
        name: 'updated_at',
        nullable: true,
        insertable: false,
        updatable: false,
        columnDefinition: 'timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        generated: "ALWAYS",
    )]
    protected DateTimeImmutable $updated;

    public function __construct()
    {
        $this->created = new \DateTimeImmutable('now');
        $this->updated = new \DateTimeImmutable('now');
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    public function getUpdated(): DateTimeImmutable
    {
        return $this->updated;
    }
}