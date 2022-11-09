<?php

namespace Action;

use Auth\Auth;
use DB\ConnectionFactory;

class ResetPassword extends Action
{

    private static string $pageEmail="<form method='post'>
email: <input type='email' name='email'><input type='submit' value='reset'>
</form>";
    private static string $pagePassword="<form method='post'>
password: <input type='password' name='password'> password egain: <input type='password' name='password2'><input type='submit' value='reset'>
</form>";

    public function execute(): string
    {
        // TODO: Implement execute() method.
        if($this->http_method=='GET'){//si il n'a pas encord saisis son email
            if(isset($_GET['token'])){//si il a reçu son token
                if(Auth::authenticateToken($_GET['token'])){//si le token marche
                    return ResetPassword::$pagePassword;//saisir nouveau mot de passe
                }else{
                    return "the token is expired";
                }
            }else{
                return ResetPassword::$pageEmail;//saisir son email
            }
        }elseif ($this->http_method=='POST'){//si il a fait entré son email
            $email=filter_var($_POST['email'],FILTER_SANITIZE_STRING);
            if(isset($_GET['token'])){//si il a donné un token
                if($_POST['password']==$_POST['password2']){//si il a saisis nouveau mot de passe double fois
                    if(Auth::checkPasswordStrength($_POST['password'])){//si la taille de mot de passe suffit
                        //renouveler son mot de passe
                        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost'=>12]);
                        $id=Auth::authenticateToken($_GET['token']);
                        ConnectionFactory::makeConnection()->exec("update utilisateur set PASSWORD = '$hash' where id = $id");
                        //laisser le client connecte
                        $url="http://localhost:63342/NetVOD/index.php?action=sign-in";
                        return "<meta http-equiv='refresh' content='0.5;url=$url'>";
                    }else{
                        return "password is not forma.<br><br>".ResetPassword::$pagePassword;
                    }
                }else{
                    return "your 2 password is not idem.<br><br>".ResetPassword::$pagePassword;
                }
            }else{
                $query=ConnectionFactory::makeConnection()->query("select id from utilisateur where email='".$email."'");
                if($query->rowCount()>0){//si le compte existe
                    //y envoyer un token
                    $tok=Auth::actualiseToken($email);
                    return "$this->hostname$this->script_name?action=reset-password&token=$tok";
                }else{
                    return "this email is not inscrited.<br><br>".ResetPassword::$pageEmail;
                }
            }
        }else{
            return 'inaccessible.';
        }
    }
}