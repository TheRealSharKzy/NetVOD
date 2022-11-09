<?php

namespace Dispatch;

use Action\ActiveAction;
use Action\EpisodeAction;
use Action\InscriptAction;
use Action\ListEnCoursAction;
use Action\SerieListEpisodeAction;
use Action\ResetPassword;
use Action\ShowCatalogueAction;
use Action\SigninAction;
use Catalogue\Episode\Episode;
use DB\ConnectionFactory;

class Dispatcher
{
    public function run(){
        $action = $_GET['action'] ?? null;

        if(!is_null($action)){
            switch ($action) {
                case "inscript":
                    $ac = new InscriptAction();
                    break;
                case "sign-in":
                    if (isset($_SESSION['user'])) {
                        unset($_SESSION['user']);
                    }
                    $ac = new SigninAction();
                    break;
                case "SerieListEpisode":
                    $ac = new SerieListEpisodeAction();
                    break;
                case "episode":
                    ListEnCoursAction::ajoutEnCours($_GET['id'],unserialize($_SESSION['user'])->id);
                    $ac = new EpisodeAction(episode::getEpById($_GET['id']));
                    break;
                case "reset-password":
                    $ac = new ResetPassword();
                    break;
                case "ListEnCours":
                    $ac = new ListEnCoursAction();
                    break;
                case "show-catalogue":
                    $ac = new ShowCatalogueAction();
                    break;
                case "active":
                    $ac=new ActiveAction();
                    break;
                case "disconnect":
                    unset($_SESSION['user']);

                    $ac = new SigninAction();
                default:
            }
        }else{
            $ac=new SigninAction();
        }

        $this->renderPage($ac->execute());

    }

    public function renderPage(string $html){
        $rubrique ='';
        if (isset($_SESSION['user'])){
            $rubrique= "
            <a href='?action=show-catalogue'>Catalogue</a>
            <a href='?action=sign-in'>Disconnect</a>";
        } else {
            $rubrique = "<a href='?action=inscript'>Register</a>                       
                            <a href='?action=sign-in'>Login</a>";
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
        <h1>NetVOD</h1>
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
        <p>SAE - Développer une application web sécurisée </p>
    </footer>
</body>
</html>
END;

    }
}