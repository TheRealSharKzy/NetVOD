<?php

namespace Action;

use DB\ConnectionFactory;

class SerieListEpisodeAction extends Action
{

    public function execute() : string{
        $html = "";
        if(isset($_GET['id'])){
            $id_serie = $_GET['id'];
        }else{
            return "Require id Serie";
        }

        $bdd = ConnectionFactory::makeConnection();
        $query = "select * from serie where id = ?";
        $statement = $bdd->prepare($query);
        $statement->bindParam(1,$id_serie);
        $statement->execute();
        if(!$resultSet = $statement->fetch(\PDO::FETCH_ASSOC)){
            return "Aucune Serie trouvee";
        }

        $titre = $resultSet['titre'];
        $descriptif = $resultSet['descriptif'];
        $chemin_img_serie = $resultSet['img'];
        $annee = $resultSet['annee'];
        $date_ajout = $resultSet['date_ajout'];


        $query_1 = "select id,numero, titre, duree, file from episode where serie_id = ?";
        $statement = $bdd->prepare($query_1);
        $statement->bindParam(1,$id_serie);
        $statement->execute();

        $html_p1 = "<h1>Episodes</h1>";
        $i = 1;
        while($resultSet = $statement->fetch(\PDO::FETCH_ASSOC)){
            $html_p1 .= <<<END
                    <div>
                        <p>Numero: $i : Titre: {$resultSet['titre']} - {$resultSet['duree']}s</p>
                        <a href="?action=episode&id={$resultSet['id']}">
                        <video width="50%" height="50%">
                            <source src = video/{$resultSet['file']}>
                        </video>
                        </a>
                    </div>

               END;
            $i ++;
        }





        $html .=<<<END
        <div>
             <img src = $chemin_img_serie alt="automne" width="50%" height="50%">
             <h1>Titre: $titre</h1>
             <h3>Descriptif: $descriptif</h3>
             <h3>Annee: $annee</h3>
             <h3>Date d'ajout: $date_ajout</h3>
            
            $html_p1
             
        </div>

        <style>
           
            
            h1 {
                font-size: 2em;
            }
            
            h3 {
                text-align: left;
            }
        
        </style>
    END;





        return $html;


    }
}