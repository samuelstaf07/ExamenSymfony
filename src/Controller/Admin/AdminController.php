<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\Post;
use App\Form\CourseFormType;
use App\Form\NewPostFormType;
use App\Repository\CommentRepository;
use App\Repository\CourseRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function admin(
        CourseRepository $courseRepository,
        CommentRepository $commentRepository,
        PostRepository $postRepository,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        Request $request,
    ): Response
    {
        $comments = $commentRepository->findAll();
        $users = $userRepository->findAll();
        $news = $postRepository->findAll();

        $slugger = new AsciiSlugger();
        $course = new Course();
        $post = new Post();

        $formNewCourse = $this->createForm(CourseFormType::class, $course);
        $formNewCourse->handleRequest($request);

        if($formNewCourse->isSubmitted() && $formNewCourse->isValid()) {
            $course->setCreatedAt(new \DateTimeImmutable())
                    ->setIsPublished(0)
                    ->setSlug($slugger->slug($course->getName()))
                    ->setProgramFile($formNewCourse->get('programFile')->getData())
            ;

            $manager->persist($course);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le commentaire a bien été ajouté'
            );
            return $this->redirectToRoute('app_admin');
        }

        $formNewPost = $this->createForm(NewPostFormType::class, $post);
        $formNewPost->handleRequest($request);

        if ($formNewPost->isSubmitted() && $formNewPost->isValid()) {
            $post->setCreatedAt(new \DateTimeImmutable())
                ->setSlug($slugger->slug($post->getName()))
                ->setImageFile($formNewPost->get('imageFile')->getData())
            ;
            $manager->persist($post);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le post a bien été ajouté'
            );
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/index.html.twig', [
            'courses' => $courseRepository->findAllOrderByDateDesc(),
            'comments' => $comments,
            'formNewCourse' => $formNewCourse,
            'formNewPost' => $formNewPost,
            'users' => $users,
            'news' => $news,
        ]);
    }
}
