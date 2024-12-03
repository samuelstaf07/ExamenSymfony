<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository, CommentRepository $commentRepository, UserRepository $userRepository, Request $request): Response
    {
        $posts = $postRepository->findBy(
            ['is_published' => true],
            ['created_at' => 'DESC'],
            3
        );

        $commentsUser = null;
        if ($this->getUser()) {
            $commentsUser = $commentRepository->findByUserId($this->getUser());
        }
        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'commentsUser' => $commentsUser,
        ]);
    }
}
