<?php

namespace list;
use serie;

class ListePreference
{
    private $liste;

    // Constructeur qui construit une liste contenant des objet du type Serie
    public function __construct()
    {
        $this->liste = array();
    }

    // Ajoute une série à la liste
    public function ajouterSerie(serie $serie)
    {
        $this->liste[] = $serie;
    }

    // Retourne la liste des séries
    public function getListe()
    {
        return $this->liste;
    }

    // Retourne le nombre de séries dans la liste
    public function getNbSeries()
    {
        return count($this->liste);
    }

    // Retourne la série à l'indice $index
    public function getSerie($index)
    {
        return $this->liste[$index];
    }

    // Retourne la liste des séries dont le nom contient $nom
    public function getSeriesByNom($nom)
    {
        $liste = new ListePreference();
        foreach ($this->liste as $serie) {
            if (strpos($serie->getNom(), $nom) !== false) {
                $liste->ajouter($serie);
            }
        }
        return $liste;
    }

}