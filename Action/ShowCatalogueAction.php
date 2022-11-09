<?php

namespace Action;

use Auth\Auth;
use Catalogue\Episode\Episode;
use Catalogue\Serie\Serie;
use DB\ConnectionFactory;

class ShowCatalogueAction extends Action
{
    private Serie $serie;
    public function execute(): string{
        $page = "";
        $bdd = ConnectionFactory::makeConnection();
        $res = $bdd->query("select * from serie");
        $res->execute();
        $series = [];
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $series[] = new Serie($row['id'],$row['nom'],$row['urlImage'],$row['description'],$row['anneeSortie'],$row['dateAjout']);
        }
        foreach ($series as $s){
            $page .= $s->nom;
        }
        return $page;
    }
}