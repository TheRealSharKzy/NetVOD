<?php

namespace Action;

use Auth\Auth;
use DB\ConnectionFactory;
use User\User;

class ProfilAction extends Action
{

    public function execute(): string
    {
        // TODO: Implement execute() method.
        if(isset($_SESSION['user'])){//si un client a connecté
            $user=unserialize($_SESSION['user']);
            $pseudo=$user->__get('pseudo');
            $email=$user->__get('email');
            $id=$user->__get('id');
        }
        if($this->http_method=='POST'){//si un client a changé quelque chose
            if($pseudo!=$_POST['pseudo']){//si il a changé le pseudo
                //mettre a jour
                $pseudo=filter_var($_POST['pseudo'],FILTER_SANITIZE_STRING);
                $sql="update utilisateur set pseudo='$pseudo' where id=$id";
                ConnectionFactory::makeConnection()->exec($sql);
                $user->__set('pseudo',$pseudo);
            }
            if($email!=$_POST['email']){//si il a changé email
                if($pseudo!=$_POST['pseudo']) {
                    $pseudo = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING);
                    $sql = "update utilisateur set pseudo='$pseudo' where id=$id";
                    ConnectionFactory::makeConnection()->exec($sql);
                }
                unset($_SESSION['user']);//déconnecter
                //mettre a jour avec token
                $tok=Auth::actualiseToken($email);
                $email=$_POST['email'];
                return "$this->hostname$this->script_name?action=profil&email=$email&token=$tok";
            }
        }elseif($this->http_method=='GET'){
            if(isset($_GET['token'])){
                //mettre a jour si token est authirisé
                $id=Auth::authenticateToken($_GET['token']);
                if($id){
                    $email=filter_var($_GET['email'],FILTER_SANITIZE_STRING);
                    $sql="update utilisateur set email='$email' where id=$id";
                    ConnectionFactory::makeConnection()->exec($sql);
                    $sql="select pseudo from utilisateur where id=$id";
                    $pseudo=ConnectionFactory::makeConnection()->query($sql)->fetch()[0];
                    $_SESSION['user']=serialize(new User($id,$pseudo,$email));
                }
            }
        }
        $page="<form method='post'>
pseudo: <input type='text' name='pseudo' value='$pseudo' required>
email: <input type='email' name='email' value='$email' required>
<input type='submit' value='Changer'>
</form>";
        return $page;
    }
}