<?php

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class OrderReferenceProvider
{
    public function __construct(private OrderNumberCreator $orderNumberCreator, private RequestStack $requestStack)
    {

    }

    public function getRequestPaymentFromProduct(Product $product): array
    {
        $reference = $this->orderNumberCreator->createToken();

        return [
            'payment' => [
                'reference' => $reference,
                'description' => 'Payment Product',
                'amount' => [
                    'currency' => 'USD',
                    'total' => $product->getPrice(),
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl' => sprintf('https://%s/order/status?reference=%s', $this->getHost($this->requestStack->getCurrentRequest()), $reference),
            'ipAddress' => $this->getClientIp(),
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        ];

    }

    private function getHost(Request $request)
    {
        $host = $request->getHttpHost();
        return $host;
    }

    private function getClientIp()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}