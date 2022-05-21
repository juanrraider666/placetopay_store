<?php

namespace App\Entity;

use App\Entity\Traits\DatesTrait;
use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`orders`')]
#[ORM\HasLifecycleCallbacks()]
class Order
{
    use DatesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $customer_name;

    #[ORM\Column(type: 'string', length: 120)]
    private $customer_email;

    #[ORM\Column(type: 'string', length: 40)]
    private $customer_mobile;

    #[ORM\Column(type: 'string', length: 20)]
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): ?string
    {
        return $this->customer_name;
    }

    public function setCustomerName(string $customer_name): self
    {
        $this->customer_name = $customer_name;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): self
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    public function getCustomerMobile(): ?string
    {
        return $this->customer_mobile;
    }

    public function setCustomerMobile(string $customer_mobile): self
    {
        $this->customer_mobile = $customer_mobile;

        return $this;
    }
}
