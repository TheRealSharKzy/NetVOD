<?php

namespace Dispatch;

use Action\EpisodeAction;
use Action\InscriptAction;
use Action\SigninAction;

class Dispatcher
{
    public function run(){

        $action = $_GET['action'] ?? null;

        if(!is_null($action)){

            switch ($action){
                case "inscript":$ac=new InscriptAction();break;
                case "sign-in":$ac=new SigninAction();break;
                case "episode":$ac=new EpisodeAction($_SESSION['serie']->getEpById($_GET['id']));break;
                default:return;
            }

            $this->renderPage($ac->execute());

        }

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
        <a href="./">Accueil</a>

        <div class="rubrique">
            <a href="html/rubrique1/index.html">Rubrique 1</a>
            <br/>
            <div class="sous">
                <a href="html/rubrique1/article1.html">Article 1</a>
                <a href="html/rubrique1/article2.html">Article 2</a>
            </div>
        </div>

        <div class="rubrique">
            <a href="html/rubrique2/index.html">Rubrique 2</a>
            <br/>
            <div class="sous">
                <a href="html/rubrique2/article3.html">Article 3</a>
                <a href="html/rubrique2/article4.html">Article 4</a>
            </div>
        </div>

        <div class="rubrique">
            <a href="html/rubrique3/index.html">Rubrique 3</a>
            <br/>
            <div class="sous">
                <a href="html/rubrique3/article5.html">Article 5</a>
                <a href="html/rubrique3/article6.html">Article 6</a>
            </div>
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