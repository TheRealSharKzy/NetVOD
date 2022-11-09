<?php

namespace Action;

use Catalogue\Episode\episode;
use DB\ConnectionFactory;

class EpisodeAction extends Action
{
    private episode $ep;

    public function __construct(episode $e)
    {
        $this->ep = $e;
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
                 
                 <button class='button b1'>Ajouter à la liste des Préférences</button>
                 
                 <h1 class='title'>Commente la série</h1>
                 <form method='post'>
                 <input id='note' class='form' type='number' value='note' placeholder='Note de la série' min='0' max='5'>
                 <input id='comment' class='form' class='form' class='form' type='text' value='commentaire' placeholder='Commentaire'>      
                 <button class='form' type='submit'>Valider</button>
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
                    
                    form{
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
                    
                    .b1:hover {
                        background-color: #FFFFFF;
                        font-size: 1.8em;
                        padding: 2%;
                        color : #00F3FF;
                        border-radius: 1.2em;
                        border: solid;
                        border-color : #00F3FF;
                        transition-duration: 0.4s;
                    }

                    .b1 {
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

        if ($_SERVER['REQUEST_METHOD']=='POST'){
            $comment = ConnectionFactory::makeConnection()->query("select * from commentaire where email =" . $_SESSION['user']->email . " and idSerie=" . $_SESSION['serie']->id);
            $comment->execute();
            if (count($comment->fetch()) != 0){
                $html.= "Vous avez déjà noté cette série";
            } else {
                ConnectionFactory::makeConnection()->exec("insert into Commentaires values(" . $_SESSION['serie']->id . "," . $_SESSION['user']->email . "," . $_POST['note'] . "," . $_POST['commentaire'] . ")");
            }
        }
        return $html;
    }
}