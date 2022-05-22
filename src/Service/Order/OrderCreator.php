<?php

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\Payment;
use App\EventListener\EventSessionPayment;
use App\Service\Order\Exceptions\ConnectionFailedException;
use App\Service\OrderNumberCreator;
use App\Service\Payment\PaymentResponse;
use App\Service\PlaceToPayManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class OrderCreator
{

    public function __construct(private PlaceToPayManager $placeToPayManager, private OrderNumberCreator $orderNumberCreator, private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function execute(Order $orderInformation): void
    {
        $this->setPayment($orderInformation);
    }

    private function setPayment(Order $order)
    {
        $placeToPay = $this->placeToPayManager->getPlaceToPay();

        $request = $this->getRequestSession();
        $response = $placeToPay->request($request);

        if ($response->isSuccessful()) {
            // STORE THE $response->requestId() and $response->processUrl() on your DB associated with the payment order
            // Redirect the client to the processUrl or display it on the JS extension
            $response->processUrl();
        } else {
            // There was some error so check the message and log it
            $status = $response->status()->message();

            throw new ConnectionFailedException($response);
        }

        dd($response);
        $this->eventDispatcher->dispatch(new EventSessionPayment(PaymentResponse::create(
            $request['payment']['reference'],
            $request['expiration'],
            120,
            $order,
            $response,
        )), EventSessionPayment::SESSION_COMPLETE_PAYMENT);

    }

    private function getRequestSession(): array
    {
        $reference = $this->orderNumberCreator->createToken();

        return [
            'payment' => [
                'reference' => $reference,
                'description' => 'Payment Product',
                'amount' => [
                    'currency' => 'USD',
                    'total' => 120,
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl' => 'https://127.0.0.1:8001/order/status?reference=' . $reference,
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        ];

    }


}