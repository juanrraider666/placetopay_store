<?php

namespace App\EventListener;

use App\Entity\Payment;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use DateTimeImmutable;

class PaymentCreatorListener
{

    private PaymentRepository $paymentRepository;
    private OrderRepository $orderRepository;

    public function __construct(PaymentRepository $paymentRepository, OrderRepository $orderRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->orderRepository = $orderRepository;
    }

    public function onPaymentCreator(EventSessionPayment $eventSessionPayment)
    {

        $responseData = $eventSessionPayment->getPaymentResponse();

        $payment = new Payment(
            $responseData->getRedirectResponse()->requestId(),
            $responseData->getAmount(),
            new DateTimeImmutable($responseData->getExpirationDate()),
            $responseData->getReference(),
            $responseData->getRedirectResponse()->processUrl(),
            $responseData->getRedirectResponse()->status()->message(),
        );

        $this->paymentRepository->add($payment);

        $order = $responseData->getOrder();
        $order->setPayment($payment);

    }
}