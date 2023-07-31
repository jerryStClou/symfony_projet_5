<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('panier', name: 'app_panier')]
    public function index(SessionInterface $session, ProduitRepository $productRepository): Response
    {
        $cart = $session->get('cart');
        $total = 0;

        $dataCart = [];
        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            $dataCart[] = [
                "product" => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrix() * $quantity;
        }
        return $this->render('panier/index.html.twig', [
            'dataCart' => $dataCart,
            'total' => $total
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
        return $this->redirectToRoute("app_panier");
    }

    #[Route('/remove/{id}', name: 'app_remove_panier')]
    public function remove(SessionInterface $session, $id): Response
    {
        $cart = $session->get('cart');
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute("app_panier");
    }

    #[Route('/delete/{id}', name: 'app_delete_panier')]
    public function delete(SessionInterface $session, $id): Response
    {
        $cart = $session->get('cart');
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute("app_panier");
    }

    #[Route('/flush', name: 'app_flush_panier')]
    public function flush(SessionInterface $session): Response
    {
        $cart = $session->set('cart', []);
        $session->set('cart', $cart);
        return $this->redirectToRoute("app_home");
    }
}
