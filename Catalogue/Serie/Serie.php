<?php

namespace Catalogue\Serie;

use Catalogue\Episode\episode;
use DB\ConnectionFactory;
use PDO;

require_once 'DB/ConnectionFactory.php';
require_once 'Catalogue/Episode/Episode.php';


class serie
{
    private array $episodes;
    private string $nom,$urlImage,$description,$anneeSortie, $dateAjout;
    private int $nbEpisodes,$id;

    public function __construct(int $id,String $nom, String $url, String $desc, String $sortie, String $ajout){
        $this->id = $id;
        $this->nom = $nom;
        $this->urlImage = $url;
        $this->description = $desc;
        $this->anneeSortie = $sortie;
        $this->dateAjout = $ajout;

        $this->getEpSerie();
    }

    public function getEpSerie()
    {
        $bdd = ConnectionFactory::makeConnection();
        $res = $bdd->query("select * from episode inner join serie on episode.serie_id = serie.id where serie.id = $this->id");
        $res->execute();

        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $this->episodes[] = new episode($row['titre'], $row['file'], $row['numero'], $row['duree'], $row['resume']);
        }

        $this->nbEpisodes = count($this->episodes);
    }

    public function getEpById(int $id) : ?episode{
        foreach($this->episodes as $key=>$val){
            if ($val->id == $id){
                return $val;
            }
        }
        return null;
    }



}