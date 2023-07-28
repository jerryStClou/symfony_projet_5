<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    #[Route('/add/{id}', name: 'app_add_panier')]
    public function ADD(SessionInterface $session, $id): Response
    {
        $cart = $session->get('cart');
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);
        return $this->render('panier/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/read', name: 'app_read_panier')]
    public function read(SessionInterface $session, ProduitRepository $productRepository): Response
    {
        $cart = $session->get('cart');
        $total = 0;
        // foreach ($cart as $id => $quantity) {
        //     $product = $productRepository->find($id);
        //     $dataCart[] = [
        //         "product" => $product,
        //         'quantity' => $quantity
        //     ];
        //     $total += $product->getPrice() * $quantity;
        // }
        return $this->render('panier/index.html.twig', [
            'cart' => $cart,
        ]);
    }
}
