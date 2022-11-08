<?php

namespace Dispatch;

use Action\EpisodeAction;
use Action\InscriptAction;
use Action\ResetPassword;
use Action\SigninAction;
use Catalogue\Episode\episode;

class Dispatcher
{
    public function run(){

        $action = $_GET['action'] ?? null;

        if(!is_null($action)){


            switch ($action){
                case "inscript":$ac=new InscriptAction();break;
                case "sign-in":$ac=new SigninAction();break;
                case "episode":$ac=new EpisodeAction(episode::getEpById($_GET['id']));break;
                case "reset-password":$ac=new ResetPassword();break;
                default:return;
            }

        }else{
            $ac=new SigninAction();
        }

        $this->renderPage($ac->execute());

    }

    public function renderPage(string $html){
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

        <div class="rubrique">
            <a href="html/rubrique2/index.html">Rubrique 2</a>
        </div>

        <div class="rubrique">
            <a href="html/rubrique3/index.html">Rubrique 3</a>
        </div>

        <a href="html/A_propos.html">A propos</a>
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