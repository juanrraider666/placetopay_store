<?php

namespace App\Request\Payment;

use App\Entity\Order;
use Dnetix\Redirection\Message\RedirectResponse;

class PaymentResponse
{

    private string $reference;
    private string $expirationDate;
    private string $amount;
    private Order $order;
    private RedirectResponse $redirectResponse;


    public function __construct(string $reference, string $expirationDate, string $amount, Order $order, RedirectResponse $redirectResponse)
    {
        $this->reference = $reference;
        $this->expirationDate = $expirationDate;
        $this->amount = $amount;
        $this->order = $order;
        $this->redirectResponse = $redirectResponse;
    }

    public static function create(string $reference, string $expirationDate, string $amount, Order $order, RedirectResponse $redirectResponse)
    {
        return new static($reference, $expirationDate, $amount, $order, $redirectResponse);
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }


    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return RedirectResponse
     */
    public function getRedirectResponse(): RedirectResponse
    {
        return $this->redirectResponse;
    }



}