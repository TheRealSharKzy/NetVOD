<?php

namespace Auth;

use DB\ConnectionFactory;
use User\User;

class Auth
{
    //verifier email et mot de pass sont correcte
    public static function authenticate(string $email,string $passwdCheck):User|null{
        $sql="select * from utilisateur where email='$email'";
        $query=ConnectionFactory::makeConnection()->query($sql);
        $row=$query->fetch();
        $hash=$row[2];
        if(password_verify($passwdCheck,$hash)){
            return new User($row[0],$row[3],$row[1]);
        }
        else return null;
    }

    //verifier un email est inscri, si non, enregister un compte
    public static function register( string $email,
                                     string $pass,$pseudo):bool {
        $sql="select * from utilisateur where email='$email'";
        $query=ConnectionFactory::makeConnection()->query($sql);
        if($query->rowCount()>0)return false;
        else{
            $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost'=>12]);
            $id=ConnectionFactory::makeConnection()->query("select * from utilisateur")->rowCount()+1;
            $sql="insert into utilisateur values ($id,'$email','$hash','$pseudo',false)";
            ConnectionFactory::makeConnection()->exec($sql);
            return true ;
        }
    }

    //stoker un client connaicté en courrant
    public static function loadProfile(User $user):void{
        $_SESSION['user'] = serialize($user);
    }

    //verifier un mot de passe est asset long
    public static function checkPasswordStrength(string $pass,
                                   int $minimumLength=6): bool {
        return strlen($pass)>=$minimumLength;
    }

    //actualiser un token d'un client
    public static function actualiseToken(string $email):string{
        $pdo=ConnectionFactory::makeConnection();

        $id=$pdo->query("select id from utilisateur where email='$email'")->fetch()[0];
        $tok=bin2hex(random_bytes(32));
        $dateexpires=time()+24*60*60;
        $hash=password_hash($tok, PASSWORD_DEFAULT, ['cost'=>12]);
        $sql="select * from token left join utilisateur on token.id=utilisateur.id where email='$email'";
        if($pdo->query($sql)->rowCount()==0){
            $pdo->exec("insert into token values ($id,'$hash',$dateexpires)");
        }else{
            $pdo->exec("update token set tok='$hash', dateexpires=$dateexpires where id=$id");
        }

        unset($pdo);
        return $tok;
    }

    //chercher un client qui correspondant un token, 0 si il n'appartient pas
    public static function authenticateToken($tokCheck):int{
        $sql="select * from token";
        $query=ConnectionFactory::makeConnection()->query($sql);
        while ($row=$query->fetch()){
            if(password_verify($tokCheck,$row[1])){
                if(time()<=$row[2])return $row[0];
                break;
            }
        }
        return 0;
    }

    //considerer un client est activé
    public static function getActive(string $email):bool{
        $sql="select active from utilisateur where email='$email'";
        return ConnectionFactory::makeConnection()->query($sql)->fetch()[0];
    }

    //motifier un client est activé
    public static function setActive(string $email, bool $active){
        $sql="update utilisateur set active=$active where email='$email'";
        ConnectionFactory::makeConnection()->exec($sql);
    }

}