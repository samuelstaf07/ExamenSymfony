<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\NewCommentFormType;
use App\Repository\CommentRepository;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourseController extends AbstractController
{
    #[Route('/courses', name: 'app_courses')]
    public function courses(CourseRepository $repository): Response
    {
        $courses = $repository->findBy(
            ['is_published' => true],
            ['created_at' => 'DESC'],
        );
        return $this->render('course/courses.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/course/{slug}', name: 'app_course')]
    public function course(?string $slug, CourseRepository $courseRepository, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = $courseRepository->findOneBy(['slug' => $slug]);
        $comments = $commentRepository->findBy(['course' => $course]);

        $newComment = new Comment();
        $form = $this->createForm(NewCommentFormType::class, $newComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($course->hasUserCommented($this->getUser())){
                $this->addFlash('danger', 'Vous avez déjà commenté ce cours.');
                return $this->redirectToRoute('app_course', ['slug' => $slug,]);
            }

            $newComment->setUser($this->getUser())
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setCourse($course)
                        ->setPublished(1);

            $entityManager->persist($newComment);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le commentaire a bien été ajouté'
            );

            return $this->redirectToRoute('app_course', [
                'slug' => $slug,
            ]);
        }

        return $this->render('course/course.html.twig', [
            'newCommentForm' => $form,
            'course' => $course,
            'comments' => $comments,
        ]);
    }

    #[Route('/editComment/{id}', name: 'app_editcomment')]
    public function editComment(Comment $comment, EntityManagerInterface $manager, Request $request): Response
    {
        if(!$comment->isPublished()){
            if($comment->getUser() === $this->getUser()){$form = $this->createForm(NewCommentFormType::class, $comment);
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                    $comment->setPublished(1);
                    $manager->flush();
                    $this->addFlash(
                        'success',
                        'Le commentaire a bien été édité');
                    return $this->redirectToRoute('app_home');
                }
                return $this->render('course/editComment.html.twig', [
                    'comment'=> $comment,
                    'form' => $form,
                ]);
            }else{
                $this->addFlash(
                    'danger',
                    'Le commentaire n\'est pas le votre');
            }
        }else{
            $this->addFlash(
                'danger',
                'Le commentaire n\'est pas désactiver');
        }
        return $this->redirectToRoute('app_home');
    }
}
