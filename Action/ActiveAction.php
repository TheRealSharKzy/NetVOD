<?php

namespace Action;

use Auth\Auth;
use DB\ConnectionFactory;
use User\User;

class ActiveAction extends Action
{

    public function execute(): string
    {
        // TODO: Implement execute() method.
        if(isset($_COOKIE['user'])){//si un client qui passe ici par guide
            $email=$_COOKIE['user'];
            if(isset($_GET['token'])){//si il a reçu son token
                if(Auth::authenticateToken($_GET['token'])){//si son token marche
                    Auth::setActive($email,true);//activer son compte

                    $sql="select * from utilisateur where email='$email'";
                    $row=ConnectionFactory::makeConnection()->query($sql)->fetch();

                    Auth::loadProfile(new User($row[0],$row[3],$row[1]));//il a connecté
                    return "your account is activated.";
                }else{
                    return "token expires.";
                }
            }else{//sinon envoyer son token à lui
                $tok=Auth::actualiseToken($email);
                return "$this->hostname$this->script_name?action=active&token=$tok";
            }
        }else{//sinon laisser il connecte
            $url="http://localhost:63342/NetVOD/index.php?action=sign-in";
            return "<meta http-equiv='refresh' content='0.5;url=$url'>";
        }

    }
}