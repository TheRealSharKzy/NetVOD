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
        if(password_verify($passwdCheck,$hash))return new User($row[0],$row[3],$row[1]);
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
            $sql="insert into utilisateur values ($id,'$email','$hash','$pseudo')";
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
}