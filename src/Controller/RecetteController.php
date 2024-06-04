<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Recette;
use App\Form\CommentType;
use App\Form\RecetteType;
use App\Form\RecetteVideoType;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\RecetteRepository;
use App\Services\NavProvinces;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    private NavProvinces $navProvinces;
    private EntityManagerInterface $entityManager;

    public function __construct(NavProvinces $navProvinces, EntityManagerInterface $entityManager)
    {
        $this->navProvinces = $navProvinces;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_recette', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new SearchData();
        $form = $this->createForm(SearchType::class, $search);

        $data = $this->entityManager->getRepository(Recette::class)->findAll();
        $recettes = $paginator->paginate($data, $request->query->getInt('page', 1), 12);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recettes = $this->entityManager->getRepository(Recette::class)->findBySearch($search);
        }

        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
            'form' => $form->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[Route('/ajouter', name: 'app_recette_new', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($recette->getLibelle());
            $recette->setLibelle($slug);
            $recette->setCreatedAt(new \DateTimeImmutable());
            $recette->setUpdatedAt(new \DateTimeImmutable());
            $recette->setAuteur($this->getUser());
            $this->entityManager->persist($recette);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre recette a été ajoutée avec succès');
            return $this->redirectToRoute('app_recette', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[Route('/{slug}', name: 'app_recette_show', methods: ['GET', 'POST'])]
    public function show($slug, RecetteRepository $recetteRepository, Request $request): Response
    {
        $recette = $recetteRepository->findOneBySlug($slug);
        $commentaire = new Commentaire();
        $commentaire->setAuteur($this->getUser());

        $commentForm = $this->createForm(CommentType::class, $commentaire);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentaire->setCreatedAt(new \DateTimeImmutable());
            $commentaire->setRecette($recette);

            $this->entityManager->persist($commentaire);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('app_recette_show', ['slug' => $recette->getSlug()]);
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'commentForm' => $commentForm->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'app_recette_edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette->setUpdatedAt(new \DateTimeImmutable());
            $recette->setAuteur($this->getUser());
            $this->entityManager->persist($recette);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre recette a été modifiée avec succès');
            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[Route('/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recette->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($recette);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
    }
}
