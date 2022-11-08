<?php

namespace Catalogue\Episode;

use Exception\InvalideProperty;

class episode
{
    private String $nom,$url,$resume;
    private int $numero,$duree;

    public function __construct(string $nom, string $url, int $numero, int $duree,String $res )
    {
        $this->nom = $nom;
        $this->url = $url;
        $this->numero = $numero;
        $this->duree = $duree;
        $this->resume = $res;
    }

    public function __get($name)
    {
        if(property_exists($this,$name))return $this->$name;
        else throw new InvalideProperty();
    }

}