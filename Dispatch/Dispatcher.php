<?php

namespace Dispatch;

use Action\EpisodeAction;
use Action\InscriptAction;
use Action\SerieListEpisodeAction;
use Action\ResetPassword;
use Action\SigninAction;
use Catalogue\Episode\Episode;

class Dispatcher
{
    public function run(){

        $action = $_GET['action'] ?? null;

//      if(!is_null($action) && isset($_SESSION['user'])){
        if(!is_null($action)){
        try {
            switch ($action) {
                case "inscript":
                    $ac = new InscriptAction();
                    break;
                case "sign-in":
                    $ac = new SigninAction();
                    break;
                case "SerieListEpisode":
                    $ac = new SerieListEpisodeAction();
                    break;
                case "episode":
                    $ac = new EpisodeAction(episode::getEpById($_GET['id']));
                    break;
                case "reset-password":
                    $ac = new ResetPassword();
                    break;
                default:
                    return;
            }
        } catch(\Error $e){
            echo $e->getTraceAsString()."<br>".$e->getMessage();
        }

        }else{
            $ac=new SigninAction();
        }

        $this->renderPage($ac->execute());

    }

    public function renderPage(string $html){
        $rubrique ='';
        if (isset($_SESSION['user'])){
            $rubrique= '<div class="rubrique">
            <a href="?action=SerieListEpisode">Liste episodes</a>
        </div>';
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
        <a href="?action=inscript">Register</a>

        <div class="rubrique">
            <a href="?action=sign-in">Login</a>
        </div>
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