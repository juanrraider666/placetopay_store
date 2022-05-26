<?php

namespace App\Controller;

use App\Entity\Product;
use App\Models\ProductModel;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/", name="app_products")
     */
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/product/{id}", name="app_product", methods={"GET"})
     */
    public function showProduct($idModel): Response
    {
//        $addToCartForm = $this->createForm(AddItemToCartFormType::class, null, [
//            'product' => $product
//        ]);
//
//        return $this->render('product/show.html.twig', [
//            'product' => $product,
//            'currentCategory' => $product->getCategory(),
//            'categories' => $categoryRepository->findAll(),
//            'addToCartForm' => $addToCartForm->createView()
//        ]);
    }
}
