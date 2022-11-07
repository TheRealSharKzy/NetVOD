<?php

use DB\ConnectionFactory;

require_once 'DB/ConnectionFactory.php';
require_once 'Catalogue/Episode/episode.php';


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

    public function getEpSerie(){
        $bdd = ConnectionFactory::makeConnection();
        $res = $bdd->query("select * from episode inner join serie on episode.serie_id = serie.id where serie.id = $this->id");
        $res->execute();

        while ($res->fetch(PDO::FETCH_ASSOC)){
            $this->episodes[] = new episode($res['titre'],$res['file'],$res['numero'],$res['duree']);
        }

        $this->nbEpisodes = count($this->episodes);
    }


}