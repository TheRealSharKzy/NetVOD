<?php

namespace Catalogue\Episode;

use DB\ConnectionFactory;
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

    public static function getEpById(int $id) : episode{
        $res = ConnectionFactory::makeConnection()->query("select * from episode where id = $id");
        $res->execute();
        $row = $res->fetch(\PDO::FETCH_ASSOC);
        return new episode($row['titre'],$row['file'],$row['numero'],$row['duree'],$row['resume']);
    }

    public function __get($name)
    {
        if(property_exists($this,$name))return $this->$name;
        else throw new InvalideProperty();
    }

    public static function getIDserie(int $id):int{
        $bdd = ConnectionFactory::makeConnection();
        return $bdd->query("select serie_id from episode where id = $id")->fetch()[0];
    }

}