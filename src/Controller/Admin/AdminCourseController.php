<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Form\CourseFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCourseController extends AbstractController
{
    #[Route('/admin/editCourse/{id}', name: 'app_editcourse')]
    public function editCourse(Course $course, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(CourseFormType::class, $course);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash(
                'success',
                'Le cours a bien été édité');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/editCourse.html.twig', [
            'course'=> $course,
            'form' => $form,
        ]);
    }

    #[Route('/admin/delCourse/{id}', name: 'app_delcourse')]
    public function delCourse(EntityManagerInterface $manager, Course $course): Response
    {
        $manager->remove($course);
        $manager->flush();
        $this->addFlash(
            'success',
            'Le cours a bien été supprimé');
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/changeVisibCourse/{id}', name: 'app_admin_changevisibcourse')]
    public function changeVisibCourse(EntityManagerInterface $manager, Course $course): Response
    {
        $course->setIsPublished(!$course->getIsPublished());
        $manager->flush();
        $this->addFlash(
            'success',
            'La visibilité du cours a bien été changé');

        return $this->redirectToRoute('app_admin');
    }
}