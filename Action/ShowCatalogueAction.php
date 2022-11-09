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
        $page = "<form method='post'>
<input type='text' name='find'><input type='submit' value='find'>
</form>";
        $bdd = ConnectionFactory::makeConnection();
        $res = $bdd->query("select * from serie");
        $res->execute();
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $titre=$row['titre'];
            if($this->http_method=='GET'||($this->http_method=='POST'&&preg_match("#".strtolower($_POST['find'])."#", strtolower($titre)))){//si le requet est GET ou le mot de chercher est correspondant
                $page .= "<div class='serie'>
                <div class='serieDesc'>
                    <h2>" . $titre . "</h2>
                </div>
                <div class='serieImg'>
                <a href='?action=SerieListEpisode&id=" . $row['id'] . "'>
                    <img src='" . $row['img'] . "' alt='' width='500' height='300'>
                </a>
                </div>
            </div>";
            }
        }
        return $page;
    }
}