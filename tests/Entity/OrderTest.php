<?php

namespace App\Tests\Entity;

use App\Entity\Order;
use App\Entity\Payment;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{

    public function testSettingEmail()
    {
        $order = new Order('juan@gmail.com', '' , '');

        $this->assertSame('juan@gmail.com', $order->getCustomerEmail());

        $order->setCustomerEmail('test@gmail.com');

        $this->assertSame('test@gmail.com', $order->getCustomerEmail());
    }


    public function testReturnsFullStatusDefault()
    {
        $order = new Order('', '' , '');

        $this->assertSame(
            Order::STATUS_CREATED,
            $order->getStatus()
        );
    }

}