<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\HomeType;
use App\Model\SearchData;
use App\Services\NavProvinces;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private NavProvinces $navProvinces;
    private EntityManagerInterface $entityManager;
    public function __construct( NavProvinces $navProvinces,EntityManagerInterface $entityManager)
    {
        $this->navProvinces = $navProvinces;
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $recettes =  $this->entityManager->getRepository(Recette::class)->findByIsBest(1);
        $search = new SearchData();
        $form = $this->createForm(HomeType::class,$search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $recettes = $this->entityManager->getRepository(Recette::class)->findBySearch($search);
            return $this->render('recette/index.html.twig',[
                'recettes'=>$recettes,
                'form'=>$form->createView(),
                'provinces' => $this->navProvinces->provinces()
            ]);
        }

        return $this->render('home/index.html.twig',[
                "recettes" => $recettes,
                "form"=>$form->createView(),
                'provinces' => $this->navProvinces->provinces()
            ]
        );
    }
}
