<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Services\NavProvinces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    private NavProvinces $navProvinces;
    public function __construct( NavProvinces $navProvinces)
    {
        $this->navProvinces = $navProvinces;
    }
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategorieRepository $categorieRepository): Response
    {
        $category = $categorieRepository->findOneBySlug($slug);
        return $this->render('category/index.html.twig', [
            'category' => $category,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }
}
