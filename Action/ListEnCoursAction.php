<?php

namespace Action;

use DB\ConnectionFactory;

class ListEnCoursAction extends Action
{


    public function execute():string{
        $html = "<div class ='LECA'><h3 id='LECA_h3'>La liste de vidéos en cours</h3><div class='LECA_1'><div class='LECA_2'>";

        $bdd = ConnectionFactory::makeConnection();
        $user = unserialize($_SESSION['user']);
        $id = $user->id;

        $resultSet_1 = $bdd->query("select id_serie from liste_epv where id = $id and estenCours = true");
        $resultSet_1->execute();

        $nb_serie = 0;
        while ($row_1 = $resultSet_1->fetch(\PDO::FETCH_ASSOC)) {
            $resultSet_2 = $bdd->query("select img,titre from serie where id = {$row_1['id_serie']}");
            $resultSet_2->execute();
            $row_2 = $resultSet_2->fetch(\PDO::FETCH_ASSOC);
            $html .= "<div class='st'><a href='?action=SerieListEpisode&id={$row_1['id_serie']}'><img src={$row_2['img']} alt={$row_2['titre']}></a>";
            $html .= "<h3>{$row_2['titre']}</h3></div>";
            $nb_serie ++;
        }

        $width = 255*$nb_serie;

        $html .= <<<END
        </div>
        </div>
        </div>
        <style>      
            .LECA_1{
                height: 270px;
                width:1000px;
                overflow: auto;
            }
            
            .LECA_2{
                border: 2px solid deepskyblue;
                width: {$width}px;
            }
            
            .st{
                text-align: left;
                display: inline-block;
            }
            
            img{
                width: 200px;
                height: 150px;
            }
            
            .LECA{
                text-align: left;
            }
            
            
            
           
            
        </style>
END;

        return $html;
    }

    public static function ajoutEnCours($id_episode,$id_user){
        $bdd = ConnectionFactory::makeConnection();
        $id_serie = $bdd->query("select serie_id from episode where id = $id_episode")->fetch()[0];

        $estvide = ($bdd->query("select count(*) from liste_epv where id = $id_user and id_serie = $id_serie")->fetch()[0] == 0);

        if($estvide){
            $bdd->exec("insert into liste_epv (id,id_serie,estenCours) values ($id_user,$id_serie,1)");
        }else{
            $estVisonne = ($bdd->query("select estvisionne from liste_epv where id = $id_user and id_serie = $id_serie")->fetch()[0] == 1);
            if(!$estVisonne){
                $bdd->exec("update liste_epv set estenCours = 1 where id = $id_user and id_serie = $id_serie");
            }
        }
    }
}