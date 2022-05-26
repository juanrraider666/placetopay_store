<?php

namespace App\Tests\Service\Order;

use App\Entity\Order;
use App\Entity\Product;
use App\Service\Order\Exceptions\ConnectionFailedException;
use App\Service\Order\OrderGenerator;
use App\Service\OrderNumberCreator;
use App\Service\OrderReferenceProvider;
use App\Service\PlaceToPayManager;
use Dnetix\Redirection\PlacetoPay;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;

class OrderGeneratorTest extends TestCase
{

    private OrderReferenceProvider $orderReferenceProvider;
    private $orderNumberCreator;
    private $requestStack;
    private $eventDispatcher;

    protected function setUp(): void
    {
        $this->orderNumberCreator = $this->prophesize(OrderNumberCreator::class);
//        $this->eventDispatcher = new EventDispatcher();
        $this->requestStack = $this->prophesize(RequestStack::class);

       $this->orderReferenceProvider = new OrderReferenceProvider(
           $this->orderNumberCreator->reveal(),
           $this->requestStack->reveal()
       );

    }

    public function testItDoesNotRequestValidPlaceToPay()
    {

        $manager = $this->createMock(PlaceToPayManager::class);
        $manager->expects($this->any())
            ->method('getPlaceToPay')
            ->willReturnCallback(function($spec) {
                return new PlacetoPay([]);
            });


        $order = $this->createOrder('test@gmail.com', '313', 'juan')->reveal();
        $product = new Product('Glasses', 100, 'Gafas para el sol', '');

        $this->assertInstanceOf(Order::class, $order);
        $this->assertInstanceOf(Product::class, $product);


        $generator = new OrderGenerator($manager,  $this->orderReferenceProvider, new EventDispatcher());

        $this->expectException(ConnectionFailedException::class);
    }



    public function createOrder(string $customer_email, string $customer_mobile, string $customer_name): ObjectProphecy
    {
        $object = $this->prophesize(Order::class);

        $object->getCustomerName()->willReturn($customer_name);
        $object->getCustomerMobile()->willReturn($customer_mobile);
        $object->getCustomerEmail()->willReturn($customer_email);

        return $object;
    }
}