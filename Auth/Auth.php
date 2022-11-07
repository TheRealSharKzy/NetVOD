<?php

namespace Auth;

require_once 'User.php';

use DB\ConnectionFactory;
use User;
use PDO;

class Auth
{
    public static function authenticate(string $email,string $passwdCheck):User|null{
        $sql="select * from User where email='$email'";
        $query=ConnectionFactory::makeConnection()->query($sql);
        $row=$query->fetch();
        $hash=$row[2];
        if(password_verify($passwdCheck,$hash))return new User($row[0],$row[3],$row[1]);
        else return null;
    }

    public static function register( string $email,
                                     string $pass):bool {
        $sql="select * from utilisateur where email='$email'";
        $query=ConnectionFactory::makeConnection()->query($sql);
        if($query->rowCount()>0)return false;
        else{
            $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost'=>12]);
            $sql="insert into User (email,password) values ('$email', '$hash')";
            ConnectionFactory::makeConnection()->exec($sql);
            return true ;
        }
    }

    public static function loadProfile(User $user):void{
        $_SESSION['user'] = serialize($user);
    }

    public static function checkPasswordStrength(string $pass,
                                   int $minimumLength): bool {
        $length = (strlen($pass) < $minimumLength); // longueur minimale
        $digit = preg_match("#[\d]#", $pass); // au moins un digit
        $special = preg_match("#[\W]#", $pass); // au moins un car. spécial
        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // au moins une majuscule
        if (!$length || !$digit || !$special || !$lower || !$upper)return false;
        return true;
    }
}