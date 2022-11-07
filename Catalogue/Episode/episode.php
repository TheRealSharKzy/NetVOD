<?php

namespace Catalogue\Episode;

class episode
{
    private String $nom,$urlImage,$resume;
    private int $numero,$duree;

    public function __construct(string $nom, string $urlImage, int $numero, int $duree,String $res )
    {
        $this->nom = $nom;
        $this->urlImage = $urlImage;
        $this->numero = $numero;
        $this->duree = $duree;
        $this->resume = $res;
    }

}