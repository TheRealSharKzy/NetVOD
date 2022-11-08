<?php

namespace Action;

use Auth\Auth;

class InscriptAction extends Action
{

    public function execute(): string
    {

        $page="<form method='post'>
<div><label>Pseudo:</label> <input type='text' name='pseudo' required></div>
<div><label>Email:</label> <input type='email' name='email' required></div>
<div><label>Password:</label> <input type='password' name='password' required></div>
<div><label>Repeat password:</label> <input type='password' name='password2' required></div>
<button type='submit'>S'inscrire</button>
</form>

 <style>
        
        div{
            display: flex;
        }
               
        input, label {
            text-align: left; 
            display: block;
         }
          
        form{
            display: inline-block;
        }
          
</style>";
        if($this->http_method=='GET'){
            return $page;
        }elseif ($this->http_method=='POST'){
            $pseudo=filter_var($_POST['pseudo'],FILTER_SANITIZE_STRING);
            $email=filter_var($_POST['email'],FILTER_SANITIZE_STRING);
            if($_POST['password']==$_POST['password2']){
                if(Auth::checkPasswordStrength($_POST['password'])){
                    if(Auth::register($email,$_POST['password'],$pseudo)){
                        $url="http://localhost:63342/NetVOD/index.php?action=sign-in";
                        return "<meta http-equiv='refresh' content='0.5;url=$url'>";
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