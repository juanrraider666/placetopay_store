<?php

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\Product;
use App\EventListener\EventSessionPayment;
use App\Request\Payment\PaymentResponse;
use App\Service\Order\Exceptions\ConnectionFailedException;
use App\Service\OrderReferenceProvider;
use App\Service\PlaceToPayManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class OrderGenerator
{

    public function __construct(private PlaceToPayManager $placeToPayManager, private OrderReferenceProvider $orderReferenceFactory, private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function setOrderProduct(Order $order, Product $product)
    {
        $placeToPay = $this->placeToPayManager->getPlaceToPay();

        $request = $this->orderReferenceFactory->getRequestPaymentFromProduct($product);

        $response = $placeToPay->request($request);

        if (!$response->isSuccessful()) {
            $status = $response->status()->message();
            throw new ConnectionFailedException($response);
            // STORE THE $response->requestId() and $response->processUrl() on your DB associated with the payment order
            // Redirect the client to the processUrl or display it on the JS extension
        }

        $this->eventDispatcher->dispatch(new EventSessionPayment(PaymentResponse::create(
            $request['payment']['reference'],
            $request['expiration'],
            $product->getPrice(),
            $order,
            $response,
        )), EventSessionPayment::SESSION_COMPLETE_PAYMENT);

    }


}