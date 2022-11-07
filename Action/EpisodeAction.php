<?php

namespace Action;

use Catalogue\Episode\episode;

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
                 <br class='title'> " . $this->ep->nom . "</br>
                 <video controls='' width='900'>
                 <source src=' ". $this->ep->url . "' type='video/mp4'>
                 </video>
                 <br class='epi'> " . $this->ep->duree . "<br>
                 <br class='epi'> " . $this->ep->resume . "</br>
                 </div>
                 
                 <style>
                    
                    body{
                      color: ;
                      background-color: black;

                    }
                    
                    video{
                        padding: 10px;
                    }
                    
                    div.info{
                        display: block;
                        text-align: center;
                        text-decoration-color: white;
                    }
                 
                 </style>
               ";

        return $html;
    }
}