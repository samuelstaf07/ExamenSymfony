<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCommentController extends AbstractController
{
    #[Route('/admin/changeVisibComment/{id}', name: 'app_admin_changevisibcomment')]
    public function changeVisibComment(EntityManagerInterface $manager, Comment $comment): Response
    {
        $comment->setPublished(!$comment->isPublished());
        $manager->flush();
        $rslt = $comment->isPublished() ? "" : ", l\'utilisateur recevra un message lors de sa connexion.";
        $this->addFlash(
            'success',
            'La visibilité du commentaire a bien été changé' . $rslt);
        return $this->redirectToRoute('app_admin');
    }
}
