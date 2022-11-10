<?php

namespace Action;

use DB\ConnectionFactory;

class ListAction extends Action
{

    public function execute(): string
    {
        $type_list = $_GET['type'];

        switch ($type_list){
            case 'EnCours' :{
                $titre_list = 'La liste de vidéos en cours';
                $text_vide = 'Il n\'y a pas de série en cours de visonnage';
                $est_condition = 'estenCours';
                break;
            }
            case 'Prefere' :{
                $titre_list = 'La liste de vidéos préférées';
                $text_vide = 'Il n\'y a pas de série préférée';

                $est_condition = 'estprefere';

                break;
            }
            case 'Visionne' :{
                $titre_list = 'La liste de vidéos déjà visionnés';
                $text_vide = 'Il n\'y a pas de série déjà visionnée';

                $est_condition = 'estvisionne';
                break;
            }
            default:{
                return "<h1>Type List inconnu</h1>";
            }

        }

        $html = "<div class ='LPA'><h3 id='LPA_h3'>$titre_list</h3><div class='LPA_1'><div class='LPA_2'>";

        $bdd = ConnectionFactory::makeConnection();
        $user = unserialize($_SESSION['user']);
        $id = $user->id;

        $resultSet_1 = $bdd->query("select id_serie from liste_epv where id = $id and $est_condition = true");
        $resultSet_1->execute();

        $nb_serie = 0;
        $height_LECA_1 = 75;
        $estVide = true;

        while ($row_1 = $resultSet_1->fetch(\PDO::FETCH_ASSOC)) {
            $resultSet_2 = $bdd->query("select img,titre from serie where id = {$row_1['id_serie']}");
            $resultSet_2->execute();
            $row_2 = $resultSet_2->fetch(\PDO::FETCH_ASSOC);
            $html .= "<div class='st'><a href='?action=SerieListEpisode&id={$row_1['id_serie']}'><img src={$row_2['img']} alt={$row_2['titre']}></a>";
            $html .= "<h3>{$row_2['titre']}</h3></div>";
            $nb_serie ++;
            $estVide = false;
        }


        if($estVide){
            $width_LECA_2 = "400";
            $html .= "<p>$text_vide</p>";
        }else{
            $width_LECA_2 = 255*$nb_serie;
            $height_LECA_1 = 275;

        }

        $html .= <<<END
        </div>
        </div>
        </div>
        <style>      
            .LPA_1{
                height: {$height_LECA_1}px;
                width:1000px;
                overflow: auto;
            }
            
            .LPA_2{
                background-color: antiquewhite;
                border: 5px solid deepskyblue;
                border-radius: 35px;
                width: {$width_LECA_2}px;
            }
            
            .st{
                text-align: left;
                display: inline-block;
            }
            
            img{
                width: 200px;
                height: 150px;
            }
            
            .LPA{
                text-align: left;
            }
            
            
            
           
            
        </style>
END;

        return $html;
    }

    /**
     * @throws \Exception La Condition inconnue
     */
    public static function ajoutCondition(int $id_episode, string $condition){
        $id_user = unserialize($_SESSION['user'])->id;

        switch ($condition){
            case 'EnCours':{
                $est_condition = 'estenCours';
                break;
            }
            case 'Prefere':{
                $est_condition = 'estprefere';
                break;
            }
            case 'Visionne':{
                $est_condition = 'estvisionne';
                break;
            }
            default:{
                throw new \Exception("condition inconnue");
            }
        }


        $bdd = ConnectionFactory::makeConnection();
        $id_serie = $bdd->query("select serie_id from episode where id = $id_episode")->fetch()[0];

        $estvide = ($bdd->query("select count(*) from liste_epv where id = $id_user and id_serie = $id_serie")->fetch()[0] == 0);

        if($estvide){
            $bdd->exec("insert into liste_epv (id,id_serie,$est_condition) values ($id_user,$id_serie,1)");
        }else{
            if($condition == 'Prefere'){
                $bdd->exec("update liste_epv set $est_condition = 1 where id = $id_user and id_serie = $id_serie");
            }else if($condition == 'Visionne'){
                $bdd->exec("update liste_epv set $est_condition = 1 where id = $id_user and id_serie = $id_serie");
                $bdd->exec("update liste_epv set estenCours = 0 where id = $id_user and id_serie = $id_serie");
            }else{ //$condition == 'EnCours'
                $estVisonne = ($bdd->query("select estvisionne from liste_epv where id = $id_user and id_serie = $id_serie")->fetch()[0] == 1);
                if(!$estVisonne){
                    $bdd->exec("update liste_epv set estenCours = 1 where id = $id_user and id_serie = $id_serie");
                }
            }
        }
    }

}