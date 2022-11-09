<?php

namespace Auth;

use DB\ConnectionFactory;
use User\User;

class Auth
{
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

    public static function register( string $email,
                                     string $pass,$pseudo):bool {
        $sql="select * from utilisateur where email='$email'";
        $query=ConnectionFactory::makeConnection()->query($sql);
        if($query->rowCount()>0)return false;
        else{
            $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost'=>12]);
            $id=ConnectionFactory::makeConnection()->query("select * from utilisateur")->rowCount();
            $sql="insert into utilisateur values ($id,'$email','$hash','$pseudo',false)";
            ConnectionFactory::makeConnection()->exec($sql);
            return true ;
        }
    }

    public static function loadProfile(User $user):void{
        $_SESSION['user'] = serialize($user);
    }

    public static function checkPasswordStrength(string $pass,
                                   int $minimumLength=6): bool {
        return strlen($pass)>=$minimumLength;
    }

    public static function actualiseToken(string $email):string{
        $pdo=ConnectionFactory::makeConnection();

        $id=$pdo->query("select id from utilisateur where email='$email'")->fetch()[0];
        $tok=bin2hex(random_bytes(32));
        $dateexpires=time()+24*60*60;

        $sql="select * from token left join utilisateur on token.id=utilisateur.id where email='$email'";
        if($pdo->query($sql)->rowCount()==0){
            $pdo->exec("insert into token values ($id,'$tok',$dateexpires)");
        }else{
            $pdo->exec("update token set tok='$tok', dateexpires=$dateexpires where id=$id");
        }

        unset($pdo);
        return $tok;
    }

    public static function authenticateToken($tokCheck):bool{
        $sql="select dateexpires from token where tok='$tokCheck'";
        $query=ConnectionFactory::makeConnection()->query($sql);
        if($query->rowCount()==0)return false;
        else{
            $dateexpires=$query->fetch()[0];
            return time()<=$dateexpires;
        }
    }

    public static function getActivite(string $email):bool{
        $sql="select activite from utilisateur where email='$email'";
        return ConnectionFactory::makeConnection()->query($sql)->fetch()[0];
    }

    public static function setActivite(string $email,bool $activite){
        $sql="update utilisateur set activite=$activite where email='$email'";
        ConnectionFactory::makeConnection()->exec($sql);
    }

}