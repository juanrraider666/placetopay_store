<?php

namespace App\EventListener;

use App\Service\Payment\PaymentResponse;

class EventSessionPayment
{
    public const SESSION_COMPLETE_PAYMENT = 'payment.creator';
    private PaymentResponse $paymentResponse;


    public function __construct(PaymentResponse $paymentResponse)
    {
        $this->paymentResponse = $paymentResponse;
    }

    public function getPaymentResponse(): PaymentResponse
    {
        return $this->paymentResponse;
    }



}