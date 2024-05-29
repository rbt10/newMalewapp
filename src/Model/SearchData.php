<?php

namespace App\Model;

class SearchData
{
    /**
     * @var int
     */
    public $page =1;

    /**
     * @var string
     */
    public  string $q ='';

    /**
     * @var array
     */
    public  array $categories = [];

    public function __toString()
    {
        return $this->q;
    }
}