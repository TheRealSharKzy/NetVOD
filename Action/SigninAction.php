<?php

namespace Action;

require_once 'User.php';

use Auth\Auth;
use DB\ConnectionFactory;

class SigninAction extends Action
{

    public function execute(): string
    {
        // TODO: Implement execute() method.
        $page="<form method='post'>
          <label>Email:</label>
          <input type=\"email\" name=\"email\" required><br><br>
          <label>mod de passe:</label>
          <input type=\"password\" name=\"password\" required><br><br>
          <button type=\"submit\">Sign in</button>
          </form>";
        if($this->http_method=='GET'){
            return $page;
        }elseif($this->http_method=='POST'){
            $user=Auth::authenticate($_POST['email'],$_POST['password']);
            if($user==null){
                $page="your email or password is incorrect.<br><br>".$page;
                return $page;
            }
            else{
                Auth::loadProfile($user);
                $action="?action=Acceuil";
                return "<meta http-equiv='refresh' content='url=$action'>";
            }
        }else{
            return "iaccessible.";
        }
    }
}