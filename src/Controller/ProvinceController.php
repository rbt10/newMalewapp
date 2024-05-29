<?php

namespace App\Controller;

use App\Entity\Provinces;
use App\Services\NavProvinces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProvinceController extends AbstractController
{
    private NavProvinces $navProvinces;
    public function __construct( NavProvinces $navProvinces)
    {
        $this->navProvinces = $navProvinces;
    }
    #[Route('/province/{slug}', name: 'app_province', methods: ['GET'])]
    public function index(Provinces $provinces): Response
    {
        $recettes = $provinces->getRecettes();

        return $this->render('province/index.html.twig', [
            'recettes' => $recettes,
            'province' =>$provinces,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }
}
