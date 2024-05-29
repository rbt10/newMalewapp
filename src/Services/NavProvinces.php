<?php

namespace App\Services;

use App\Repository\ProvincesRepository;

class NavProvinces
{
    private ProvincesRepository $provinceRepository;

    /**
     * @param ProvincesRepository $provinceRepository
     */
    public function __construct(ProvincesRepository $provinceRepository)
    {
        $this->provinceRepository = $provinceRepository;
    }

    public function provinces(){
        return $this->provinceRepository->findAll();
    }


}