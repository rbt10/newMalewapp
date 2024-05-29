<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\RecetteRepository;
use App\Services\NavProvinces;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    private NavProvinces $navProvinces;
    private EntityManagerInterface $entityManager;
    public function __construct( NavProvinces $navProvinces,EntityManagerInterface $entityManager)
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
        $recettes = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),12);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $recettes = $this->entityManager->getRepository(Recette::class)->findBySearch($search);
        }


        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
            'form'=>$form->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[Route('/ajouter', name: 'app_recette_new', requirements: ['id'=> Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
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
            $entityManager->persist($recette);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été ajoutée avec succès'
            );
            return $this->redirectToRoute('app_recette', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[Route('/{slug}', name: 'app_recette_show', methods: ['GET'])]
    public function show($slug, RecetteRepository $recetteRepository): Response
    {
        $recette = $recetteRepository->findOneBySlug($slug);

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', requirements: ['id'=> Requirement::DIGITS], methods: ['GET', 'POST'])]
    #[IsGranted(new Expression('is_granted("USER") or is_granted("ROLE_MANAGER")'))]

    public function edit(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette->setCreatedAt(new \DateTimeImmutable());
            $recette->setUpdatedAt(new \DateTimeImmutable());
            $recette->setAuteur($this->getUser());
            $entityManager->persist($recette);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été ajoutée avec succès'
            );
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[Route('/{id}', name: 'app_recette_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mes-recettes', name: 'app_mes_recettes')]
    #[IsGranted('ROLE_USER')]
    public function mesRecettes(RecetteRepository $recetteRepository): Response
    {
        $user = $this->getUser();
        $recettes = $recetteRepository->findBy(['user' => $user]);

        return $this->render('recette/mesrecettes.html.twig', [
            'recettes' => $recettes,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

}
