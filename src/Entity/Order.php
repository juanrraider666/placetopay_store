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

    public const STATUS_CREATED = 'CREATED';
    public const STATUS_PAYED = 'PAYMENT';
    public const STATUS_REJECTED = 'REJECTED';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $customer_name;

    #[ORM\Column(type: 'string', length: 120)]
    private ?string $customer_email;

    #[ORM\Column(type: 'string', length: 40)]
    private ?string $customer_mobile;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status;

    #[ORM\OneToOne(targetEntity: Payment::class, cascade: ['persist', 'remove'])]
    private $payment;

    public function __construct(?string $customer_email = null, ?string $customer_mobile = null, ?string $customer_name = null)
    {
        $this->customer_email = $customer_email;
        $this->customer_mobile = $customer_mobile;
        $this->customer_name = $customer_name;
        $this->status = self::STATUS_CREATED;
    }

    public static function createOrder(string $customer_email, string $customer_mobile, string $customer_name)
    {
        return new static($customer_email, $customer_mobile, $customer_name);
    }

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

    public function changeStatus($status)
    {
        $this->status = $status;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }
}
