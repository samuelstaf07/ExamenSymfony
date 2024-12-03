<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\NewPostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminNewsController extends AbstractController
{
    #[Route('/admin/editPost/{id}', name: 'app_editpost')]
    public function editPost(Post $post, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(NewPostFormType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash(
                'success',
                'Le cours a bien été édité');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/editCourse.html.twig', [
            'news'=> $post,
            'form' => $form,
        ]);
    }

    #[Route('/admin/delPoste/{id}', name: 'app_delpost')]
    public function delPost(EntityManagerInterface $manager, Post $post): Response
    {
        $manager->remove($post);
        $manager->flush();
        $this->addFlash(
            'success',
            'Le cours a bien été supprimé');
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/changeVisibPoste/{id}', name: 'app_admin_changevisibpost')]
    public function changeVisibPost(EntityManagerInterface $manager, Post $post): Response
    {
        $post->setPublished(!$post->isPublished());
        $manager->flush();
        $this->addFlash(
            'success',
            'La visibilité du cours a bien été changé');

        return $this->redirectToRoute('app_admin');
    }
}
