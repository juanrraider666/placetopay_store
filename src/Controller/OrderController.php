<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderUserType;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use App\Service\Order\OrderCreator;
use App\Service\PlaceToPayManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order/', name: 'app_order_')]
class OrderController extends AbstractController
{

    #[Route('', name: 'list')]
    public function index(Request $request, OrderRepository $orderRepository): Response
    {
        $status = $request->get('status', 'REJECTED');

        return $this->render('order/list.html.twig', [
            'orders' => $orderRepository->findAll(),
            'status' => $status,
        ]);
    }

    #[Route('resume/{order}', name: 'resume')]
    public function resume(Order $order)
    {
        return $this->render('order/resume.html.twig', [
            'payment' => $order->getPayment(),
        ]);
    }

    #[Route('status', name: 'status')]
    public function status(Request $request, PlaceToPayManager $placeToPayManager, PaymentRepository $paymentRepository)
    {
        $placetopay = $placeToPayManager->getPlaceToPay();
        $payment = $paymentRepository->findOneBy(['reference' => $request->query->get('reference')]);

        $response = $placetopay->query($payment->getRequest());

        return $this->render('order/status.html.twig', [
            'status' => $response->status(),
        ]);
    }

    #[Route('new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager, OrderCreator $creator, OrderRepository $orderRepository)
    {
        $form = $this->createForm(OrderUserType::class)
                ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Order $order */
            $order = $form->getData();

            $creator->execute($order);
            $orderRepository->save($order);

            return $this->redirectToRoute('app_order_resume', ['order' => $order->getId()]);
        }

        return $this->render('order/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
