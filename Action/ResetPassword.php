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
        if($this->http_method=='GET'){
            if(isset($_GET['token'])){
                return ResetPassword::$pagePassword;
            }else{
                return ResetPassword::$pageEmail;
            }
        }elseif ($this->http_method=='POST'){
            if(isset($_GET['token'])){
                if($_POST['password']==$_POST['password2']){
                    if(Auth::checkPasswordStrength($_POST['password'])){
                        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost'=>12]);
                        $id=ConnectionFactory::makeConnection()->query("select id from token where tok='".$_GET['token']."'")->fetch()[0];
                        ConnectionFactory::makeConnection()->exec("update utilisateur set PASSWORD = '$hash' where id = $id");

                        return "resussi.";
                    }else{
                        return "password is not forma.<br><br>".ResetPassword::$pagePassword;
                    }
                }else{
                    return "your 2 password is not idem.<br><br>".ResetPassword::$pagePassword;
                }
            }else{

                $tok=bin2hex(random_bytes(32));
                $dateexpires=time()+24*60*60;
                $id=ConnectionFactory::makeConnection()->query("select id from utilisateur where email='".$_POST['email']."'")->fetch()[0];
                ConnectionFactory::makeConnection()->exec("insert into token values ($id,'$tok',$dateexpires)");

                return "$this->hostname$this->script_name?action=reset-password&token=$tok";
            }
        }else{
            return 'inaccessible.';
        }
    }
}