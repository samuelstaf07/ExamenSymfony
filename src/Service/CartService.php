<?php

namespace App\Service;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
class CartService
{
    private $session;
    private $entityManager;

    /**
     * @param $session
     * @param $entityManager
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function addCourseToCart(int $id): void
    {
        $cart = $this->session->get('Cart', []);
        $cart[] = $id;
        $this->session->set('Cart', $cart);
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    public function getCart(){
        return $this->session->get('Cart', []);
    }

    public function clearCart(): void
    {
        $this->session->set('Cart', []);
    }
}