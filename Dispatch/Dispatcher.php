<?php

namespace Dispatch;

use Action\AcceuilAction;
use Action\ActiveAction;
use Action\EpisodeAction;
use Action\InscriptAction;
use Action\ListAction;
use Action\ProfilAction;
use Action\SerieListEpisodeAction;
use Action\ResetPassword;
use Action\ShowCatalogueAction;
use Action\SigninAction;
use Auth\Auth;
use Catalogue\Episode\Episode;
use User\User;

class Dispatcher
{
    public function run(){
        $action = $_GET['action'] ?? null;



        if(!is_null($action)){
            switch ($action) {
                case "inscript":
                    $ac = new InscriptAction();
                    break;
                case "SerieListEpisode":
                    User::checkLogin();
                    $ac = new SerieListEpisodeAction();
                    break;
                case "episode":
                    User::checkLogin();

                    $id_serie = Episode::getIDserie($_GET['id']);
                    ListAction::ajoutCondition($id_serie,'EnCours');
                    $ac = new EpisodeAction(episode::getEpById($_GET['id']));
                    break;
                case "reset-password":
                    $ac = new ResetPassword();
                    break;
                case "List":
                    User::checkLogin();
                    $type = $_GET['type'];
                    $ac = new ListAction($type); //besoin type : EnCours,Prefere,Visionne dans $_GET
                    break;
                case "show-catalogue":
                    User::checkLogin();
                    $ac = new ShowCatalogueAction();
                    break;
                case "active":
                    $ac=new ActiveAction();
                    break;
                case "acceuil":
                    User::checkLogin();
                    $ac = new AcceuilAction();
                    break;
                case "sign-in":
                    if(isset($_SESSION['user']))unset($_SESSION['user']);
                    $ac = new SigninAction();
                    break;
                case "profil":
                    if(isset($_GET["token"])&&!Auth::authenticateToken($_GET["token"]))User::checkLogin();
                    $ac=new ProfilAction();
                    break;
                default:
                    $ac = new SigninAction();
                    break;
            }
        }else{
            $ac=new SigninAction();
        }

        $this->renderPage($ac->execute());

    }

    public function renderPage(string $html){
        $rubrique ='';
        if (isset($_SESSION['user'])){
            $rubrique= "<a href='?action=acceuil'>Acceuil</a>
            <a href='?action=show-catalogue'>Catalogue</a>
            <a href='?action=profil'>Profil</a>           
            <a href='?action=sign-in'>D??connexion</a>";
        } else {
            $rubrique = "<a href='?action=inscript'>S'enregistrer</a>                       
                            <a href='?action=sign-in'>Se connecter</a>";
        }

        echo <<<END

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>NetVOD</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>

    <header>
        <h1><a id="Header1" href = ?action=acceuil>NetVOD</a></h1>
    </header>

    <nav>      
        
        $rubrique
    </nav>

    <main>
        <article>
            <section>
            $html
            </section>
        </article>
    </main>

    <footer>
        <p>Yu TIAN, Xin ZHANG, Damien MELCHIOR, Thomas BOILLOT, Alex COLLIN - Groupe D</p>
        <p>07/11/2022</p>
        <p>SAE - D??velopper une application web s??curis??e </p>
    </footer>
</body>
</html>
END;

    }
}