<?php

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\Payment;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;

class ChangeStatus
{
    public function __construct(private OrderRepository $orderRepository, private PaymentRepository $paymentRepository)
    {
    }

    public function changeStatusOrder(Order $order, string $statusRedirectInformation)
    {
        $order->changeStatusByRedirectInformation($statusRedirectInformation);
        $this->orderRepository->add($order, true);
    }

    public function changeStatusPayment(Payment $payment, string $statusRedirectInformation)
    {
        $payment->setStatus($statusRedirectInformation);
        $this->paymentRepository->add($payment, true);
    }
}