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
        $page = "<form method='get'>
<input type='text' ><input type='submit' value='find'>
</form>
tir par:
<menu>
<a href='?action=show-catalogue&tir=titre'><li>titre</li></a>
<a href='?action=show-catalogue&tir=annee'><li>yeur</li></a>
<a href='?action=show-catalogue&tir=date-ajout'><li>date make</li></a>
<a href='?action=show-catalogue&tir=duree'><li>time</li></a>
<a href='?action=show-catalogue&tir=nb-episode'><li>number of episode</li></a>
<a href='?action=show-catalogue'><li>default</li></a>
</menu>";
        $bdd = ConnectionFactory::makeConnection();
        switch ($_GET['tir']){
            case 'titre':
                $sql="select * from serie order by titre";break;
            case 'annee':
                $sql="select * from serie order by annee";break;
            case 'date-ajout':
                $sql="select * from serie order by date_ajout";break;
            case 'duree':
                $sql="select serie.id,serie.titre,img,sum(duree) from serie left join episode on serie.id=serie_id group by serie.id,serie.titre,img order by sum(duree)";break;
            case 'nb-episode':
                $sql="select serie.id,serie.titre,img,count(episode.id) from serie left join episode on serie.id=serie_id group by serie.id,serie.titre,img order by count(episode.id)";break;
            default:
                $sql="select * from serie";break;
        }
        $res = $bdd->query($sql);
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $titre=$row['titre'];
            if(!isset($_GET['find'])&&preg_match("#".strtolower($_GET['find'])."#", strtolower($titre))){//si le mot de chercher est correspondant
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