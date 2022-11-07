<?php

class episode
{
    private String $nom,$urlImage;
    private int $numero,$duree;

    public function __construct(string $nom, string $urlImage, int $numero, int $duree)
    {
        $this->nom = $nom;
        $this->urlImage = $urlImage;
        $this->numero = $numero;
        $this->duree = $duree;
    }

}