<?php

namespace App\Controller;

use App\Entity\Recette;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    public function like(Recette $recette, EntityManagerInterface $entityManager, Request $request): Response
    {
        $currentUser = $this->getUser();
        $recette->addLiked($currentUser);
        $entityManager->persist($recette);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));

    }

    #[Route('/unlike/{id}', name: 'app_unlike')]
    public function unlike(Recette $recette, EntityManagerInterface $entityManager, Request $request): Response
    {
        $currentUser = $this->getUser();
        $recette->removeLiked($currentUser);
        $entityManager->persist($recette);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));

    }
}
