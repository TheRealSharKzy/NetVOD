<?php

namespace Action;

use Catalogue\Episode\episode;
use DB\ConnectionFactory;
use User\User;

class EpisodeAction extends Action
{
    private episode $ep;
    private bool $add;

    public function __construct(episode $e)
    {
        $this->ep = $e;
        $this->add = $this->loadadd($e);
    }


    public function execute(): string
    {
        $html = "<div class='info'>
                 <h1 id='title'> " . $this->ep->nom . "</h1>
                 <video controls='' width='900'>
                 <source src='video/{$this->ep->url}' type='video/mp4'>
                 </video>
                 <br class='epi'> durée : " . $this->ep->duree . "s<br>
                 <br class='epi'> " . $this->ep->resume . "</br>
                 </div>
              
                 <form method='post'>
                 {$this->createbutton()}
                 </form> 
                 
                      
                 
                 <script>
                    function b1(){
                        if (add){
                            document.getElementById('b1').value = 'Retirer de la liste de préférences';
                            add = true;
                            {$this->button1()}
                        }
                        else{
                            document.getElementById('b1').value = 'Ajouter à la liste de préférences';
                            add = false;
                            {$this->button1()}
                        }
                    }
                 </script>
               
                 <h1 class='title'>Commente la série</h1>
                 <form method='post' class='CommentForm'>
                 <input id='note' class='form' type='number' name='note' placeholder='Note de la série' min='0' max='5'>
                 <input id='comment' class='form' type='text' name='commentaire' placeholder='Commentaire'>      
                 <button class='form' type='submit' name='commentButton'>Valider</button>
                 </form>

                 
                 <style>
                    
                    section{
                      color: white;
                      background-color: black;
                    }
                    
                    video{
                        padding: 10px;
                    }
                    
                    #comment{
                        width: 50%;
                        height: 100px;
                    }
                    
                    div.info{
                        display: block;
                        text-align: center;
                    }
                    
                    .form {
                        display: block; 
                        margin: 10px;                                                                         
                    }
                    
                    .CommentForm{
                        border: 3px solid red;
                        padding: 10px;                        
                    }
                    
                    .title{
                        text-align: left;
                        color: red;
                        font-family: cursive;  
                        font-size: 40px;                                          
                    }
                    
                    #note{
                        width: 50px;
                        height: 25px;
                    }
                                      
                    #title{
                        font-size: 50px;                      
                    }
                    
                    #b1:hover {
                        background-color: #FFFFFF;
                        font-size: 1.8em;
                        padding: 2%;
                        color : #00F3FF;
                        border-radius: 1.2em;
                        border: solid;
                        border-color : #00F3FF;
                        transition-duration: 0.4s;
                    }

                    #b1 {
                        background-color: #00F3FF;
                        font-size: 1.8em;
                        padding: 2%;
                        color : #FFFFFF;
                        border-radius: 1.2em;
                        border: solid;
                        transition-duration: 0.4s;
                    }
                                      
                 </style>                                

        ";


        if (isset($_POST['commentButton'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $comment = ConnectionFactory::makeConnection()->prepare("select count(*) from Commentaires where email = ? and idSerie=?");
                $user = unserialize($_SESSION['user']);
                $idSerie = $_SESSION['idserie'];
                $comment->bindParam(1, $user->email);
                $comment->bindParam(2, $idSerie);
                $comment->execute();
                if ($comment->fetch()[0] != 0) {
                    $html .= "Vous avez déjà noté cette série";
                } else {
                    ConnectionFactory::makeConnection()->exec("insert into Commentaires values ({$_SESSION['idserie']},'{$user->email}',{$_POST['note']},'{$_POST['commentaire']}')");
                    $html .= "Merci pour votre commentaire";
                }
            }
        }
        return $html;
    }

    function loadadd(episode $e){
        $user = unserialize($_SESSION['user']);
        $idSerie = $_SESSION['idserie'];
        $add = ConnectionFactory::makeConnection()->prepare("select estprefere from liste_epv where id = ? and id_serie=?");
        $add->bindParam(1, $user->id);
        $add->bindParam(2, $idSerie);
        $add->execute();
        if ($add->fetch()[0] != 0) {
            return true;
        }
        else{
            return false;
        }
    }

    function createbutton() : string{
        if ($this->add){
            return "<input type='submit' name='button1' id='b1' onclick='b1' value='Retirer de la liste de préférences'>";
        }
        else{
            return "<input type='submit' name='button1' id='b1' onclick='b1' value='Ajouter à la liste de préférences'>";
        }
    }

    function button1(){
        $user = unserialize($_SESSION['user']);
        $idSerie = $_SESSION['idserie'];
        if ($this->add){
            ConnectionFactory::makeConnection()->exec("update liste_epv set estprefere=null where id = '{$user->id}' and id_serie={$idSerie}");
        }
        else{
            $exist = ConnectionFactory::makeConnection()->query("select count(*) from liste_epv where id = '{$user->id}' and id_serie={$idSerie}");
            $exist->execute();
            if ($exist->fetch()[0]==0) {
                ConnectionFactory::makeConnection()->exec("insert into liste_epv(id,id_serie,estprefere) values ({$user->id},'{$idSerie}',1)");
            } else {
                ConnectionFactory::makeConnection()->exec("update liste_epv set estprefere=1 where id = '{$user->id}' and id_serie={$idSerie}");
            }
        }
    }
}