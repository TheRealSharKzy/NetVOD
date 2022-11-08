<?php

namespace Catalogue\list;

use Catalogue\Serie\serie;

class ListePreference
{
    private $liste;

    // Constructeur qui construit une liste contenant des objet du type Serie
    public function __construct()
    {
        $this->liste = array();
    }

    // Ajoute une série à la liste de préférence si elle n'y est pas déjà
    public function ajouterSerie(serie $serie)
    {
        if (!$this->SerieDejaPresenteDansListe($serie)) {
            $this->liste[] = $serie;
        }
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

    // Retourne true si la série $serie est dans la liste
    public function SerieDejaPresenteDansListe(serie $serie) : bool
    {
        foreach ($this->liste as $serieListe) {
            if ($serieListe->getId() == $serie->getId()) {
                return true;
            }
        }
        return false;
    }

}