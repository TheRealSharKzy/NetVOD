<?php

namespace Action;

use Auth\Auth;

class SigninAction extends Action
{

    public function execute(): string
    {
        $page="<form method='post'>
          <table>
            <tr>
                <th><label>Email:</label></th>
</tr>
          
</table>
        
          <label>Email:</label>
          <input type=\"email\" name=\"email\" required><br><br>
          <label>mod de passe:</label>
          <input type=\"password\" name=\"password\" required><br><br>
          <button type=\"submit\">Sign in</button>
          </form>
        
          <style>
          form {
          display: inline-block;
          text-align: left;
          }
          
</style>
        
        ";
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
                $url="http://localhost:63342/NetVOD/acceuil.html";
                return "<meta http-equiv='refresh' content='0.5;url=$url'>";
            }
        }else{
            return "inaccessible.";
        }
    }
}