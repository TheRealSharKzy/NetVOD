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
            $email=filter_var($_POST['email'],FILTER_SANITIZE_STRING);
            if(isset($_GET['token'])){
                if($_POST['password']==$_POST['password2']){
                    if(Auth::checkPasswordStrength($_POST['password'])){
                        $dateexpires=ConnectionFactory::makeConnection()->query("select dateexpires from token where tok='".$_GET['token']."'")->fetch()[0];
                        if(time()<=$dateexpires){
                            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost'=>12]);
                            $id=ConnectionFactory::makeConnection()->query("select id from token where tok='".$_GET['token']."'")->fetch()[0];
                            ConnectionFactory::makeConnection()->exec("update utilisateur set PASSWORD = '$hash' where id = $id");

                            return "resussi.";
                        }else{
                            return "the token is expired";
                        }
                    }else{
                        return "password is not forma.<br><br>".ResetPassword::$pagePassword;
                    }
                }else{
                    return "your 2 password is not idem.<br><br>".ResetPassword::$pagePassword;
                }
            }else{
                $query=ConnectionFactory::makeConnection()->query("select id from utilisateur where email='".$email."'");
                if($query->rowCount()>0){
                    $tok=bin2hex(random_bytes(32));
                    $dateexpires=time()+24*60*60;
                    $id=$query->fetch()[0];
                    ConnectionFactory::makeConnection()->exec("insert into token values ($id,'$tok',$dateexpires)");
                    //return "<a href='$this->hostname$this->script_name?action=reset-password&token=$tok'>RESETTTT</a>";
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