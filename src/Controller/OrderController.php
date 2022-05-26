<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderUserType;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use App\Repository\ProductRepository;
use App\Service\Order\ChangeStatus;
use App\Service\Order\OrderGenerator;
use App\Service\PlaceToPayManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order', name: 'app_order_')]
class OrderController extends AbstractController
{

    #[Route('/', name: 'list')]
    public function index(Request $request, OrderRepository $orderRepository): Response
    {
        $status = $request->get('status', 'REJECTED');

        return $this->render('order/list.html.twig', [
            'orders' => $orderRepository->findAll(),
            'status' => $status,
        ]);
    }

    #[Route('/resume/{order}', name: 'resume')]
    public function resume(Order $order)
    {
        return $this->render('order/resume.html.twig', [
            'payment' => $order->getPayment(),
        ]);
    }

    #[Route('/status', name: 'status')]
    public function status(
        Request $request,
        PlaceToPayManager $placeToPayManager,
        PaymentRepository $paymentRepository,
        OrderRepository $orderRepository,
        ChangeStatus $changeStatus
    )
    {
        $placeToPay = $placeToPayManager->getPlaceToPay();
        $payment = $paymentRepository->findOneBy(['reference' => $request->query->get('reference')]);
        $order = $orderRepository->findOneBy(['payment' => $payment]);

        $response = $placeToPay->query($payment->getRequest());

        $changeStatus->changeStatusOrder($order, $response->status()->status());
        $changeStatus->changeStatusPayment($payment, $response->status()->message());

        return $this->render('order/status.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/my-order-status/{order}', name: 'my_status')]
    public function myOrderStatus(Order $order)
    {
        return $this->render('order/status.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/new/{product}', name: 'new')]
    public function new(Request $request, Product $product, OrderGenerator $creator, OrderRepository $orderRepository)
    {
        $form = $this->createForm(OrderUserType::class, new Order('', '', ''))
                ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Order $order */
            $order = $form->getData();
            $creator->setOrderProduct($order, $product);
            $orderRepository->save($order);

            return $this->redirectToRoute('app_order_resume', ['order' => $order->getId()]);
        }

        return $this->render('order/new.html.twig', [
            'form' => $form->createView(),
            'featuredProduct' => $product,
        ]);
    }
}
