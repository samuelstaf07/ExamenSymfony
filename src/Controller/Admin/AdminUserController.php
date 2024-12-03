<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/disableUser/{id}', name: 'app_admin_changevisibilityUser')]
    public function changevisibilityUser(EntityManagerInterface $manager, User $user): Response
    {
        if( (
                in_array('ROLE_ADMIN', $this->getUser()->getRoles()) &&
                !in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())
            )
            && (
                in_array('ROLE_SUPER_ADMIN', $user->getRoles()) ||
                in_array('ROLE_ADMIN', $user->getRoles()))
            )
        {
            $this->addFlash(
                'danger',
                'Vous ne pouvez pas désactiver un admin !',
            );
            return $this->redirectToRoute('app_admin');
        }

        $user->setDisabled(!$user->getIsDisabled());
        $manager->flush();

        $rslt = $user->getIsDisabled() ? "désactivé" : "activé";

        $this->addFlash(
            'success',
            'L\'utilisateur est maintenant ' . $rslt,
        );

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/superadmin/promote/{id}', name: 'app_super_admin_promote')]
    public function promoteUser(EntityManagerInterface $manager, User $user, Request $request): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $newRole = $user->getRoles();

        if(!in_array("ROLE_ADMIN", $newRole)){
            $newRole[] = "ROLE_ADMIN";
            $user->setRoles($newRole);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a été promu en ROLE_ADMIN'
            );
        }

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/superadmin/demote/{id}', name: 'app_super_admin_demote')]
    public function demoteUser(EntityManagerInterface $manager, User $user, Request $request): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $newRole = [];

        if(in_array("ROLE_ADMIN", $user->getRoles())){
            $newRole[] = "ROLE_USER";
            $user->setRoles($newRole);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a été rétrogradé en ROLE_USER'
            );
        }

        return $this->redirectToRoute('app_admin');
    }
}
