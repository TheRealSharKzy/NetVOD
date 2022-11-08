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

        $html=<<<END
        <table>
        
        
        </table>
END;





        return $html;


    }
}