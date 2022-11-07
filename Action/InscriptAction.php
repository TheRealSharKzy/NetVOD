<?php

namespace Action;

use Auth\Auth;

class InscriptAction extends Action
{

    public function execute(): string
    {
        // TODO: Implement execute() method.
        $page="<form method='post'>
pseudo: <input type='text' name='pseudo' required>
email: <input type='email' name='email' required>
password: <input type='password' name='password' required>
password egin: <input type='password' name='password2' required>
</form>";
        if($this->http_method=='GET'){
            return $page;
        }elseif ($this->http_method=='POST'){
            if($_POST['password']==$_POST['password2']){
                if(Auth::checkPasswordStrength($_POST['password'])){
                    if(Auth::authenticate($_POST['email'],$_POST['password'])){
                        return "<a href='?action=sign-in'>Sign in</a>";
                    }else{
                        return "email exist.<br><br>".$page;
                    }
                }else{
                    return "password is not forma.<br><br>".$page;
                }
            }else{
                return "your 2 password is not idem.<br><br>".$page;
            }
        }else{
            return "inaccessibly";
        }
    }
}