<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SetProductDefaultEventSubscriber implements EventSubscriberInterface
{

    public function __construct(private ProductRepository $productRepository)
    {
    }


    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {

        $products = $this->productRepository->findAll();

        if(count($products)) {
            return;
        }

        /** set data default data product **/
        $product = new Product(
            'Glasses',
            170,
            'glasses to see you great ❤️!',
            'https://picsum.photos/500/300',
        );

        $this->productRepository->add($product, true);
    }
}