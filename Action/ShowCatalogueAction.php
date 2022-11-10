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
        if(isset($_POST['find'])||isset($_GET['find'])){//si un client a cherché cataloque par mot clé, ajouter le dans url
            $find= $_POST['find'] ?? $_GET['find'];
            $menu="<menu>
<a href='?action=show-catalogue&tir=titre&find=$find'><li>titre</li></a>
<a href='?action=show-catalogue&tir=annee&find=$find'><li>année</li></a>
<a href='?action=show-catalogue&tir=date-ajout&find=$find'><li>date d'ajout</li></a>
<a href='?action=show-catalogue&tir=duree&find=$find'><li>Année de création</li></a>
<a href='?action=show-catalogue&tir=nb-episode&find=$find'><li>Nombre d'épisodes</li></a>
<a href='?action=show-catalogue&find=$find'><li>Par défaut</li></a>
</menu>";
        }else {
            $menu = "<menu>
<a href='?action=show-catalogue&tir=titre'><li>titre</li></a>
<a href='?action=show-catalogue&tir=annee'><li>année</li></a>
<a href='?action=show-catalogue&tir=date-ajout'><li>date d'ajout</li></a>
<a href='?action=show-catalogue&tir=duree'><li>Année de création</li></a>
<a href='?action=show-catalogue&tir=nb-episode'><li>Nombre d'épisodes</li></a>
<a href='?action=show-catalogue'><li>Par défaut</li></a>
</menu>";
        }

        $tir= $_GET['tir'] ?? 'titre';
        //page html base
        $page = "<form method='post'>
<input type='text' name='find'><input type='submit' value='Rechercher'>
</form>
<form method='post' action='?action=show-catalogue&tir=$tir'>
<input type='submit' value='Tout montrer'>
</form>

<div class='menu'>
trier par:
$menu</div>
<style>
.menu{
    text-align: left;       
}

li{
display: inline;
}

</style>";
        $bdd = ConnectionFactory::makeConnection();
        $tir= $_GET['tir'] ?? 'titre';
        switch ($tir){//tir les cataloge selon un choix de client
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
            $post_find = $_POST['find'] ?? "";

            $titre=$row['titre'];
            if($this->http_method=='GET'&&(!isset($_GET['find'])||
                    preg_match("#".strtolower($_GET['find'])."#", strtolower($titre)))||
                $this->http_method=='POST'&&preg_match("#".strtolower($post_find)."#", strtolower($titre)))
            {//si le mot de chercher est correspondant
                //ajouter dans la page
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