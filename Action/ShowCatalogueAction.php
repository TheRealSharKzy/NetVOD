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
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $page .= "<div class='serie'>
                <div class='serieDesc'>
                    <h2>" . $row['titre'] . "</h2>
                </div>
                <div class='serieImg'>
                <a href='?action=SerieListEpisode&id=" . $row['id'] . "'>
                    <img src='" . $row['img'] . "' alt='' width='500' height='300'>
                </a>
                </div>
            </div>";
        }
        return $page;
    }
}