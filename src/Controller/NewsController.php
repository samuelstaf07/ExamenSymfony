<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function index(PostRepository $postRepository, Request $request, PaginatorInterface
    $paginator): Response
    {
        $posts = $postRepository->findBy(
            ['is_published' => true],
            ['created_at' => 'DESC'],
        );

        $pagination = $paginator->paginate($posts, $request->query->getInt('page', 1),
            10
        );

        return $this->render('news/news.html.twig', [
            'posts' => $pagination
        ]);
    }

    #[Route('/post/{id}', name: 'app_post')]
    public function post(?int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBy(['id' => $id]);
        return $this->render('news/post.html.twig', [
            'post' => $post,
        ]);
    }
}
