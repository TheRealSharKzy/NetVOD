<?php

namespace Action;

use Auth\Auth;

class SigninAction extends Action
{

    public function execute(): string
    {
        $page="<table>
                <tbody>
               <form method='post'>          
                <tr>
                    <th><label>Email:</label></th>
                    <td><input type=\"email\" name=\"email\" required></td>
                </tr>
                <tr>
                    <th><label>mot de passe:</label></th>
                    <td> <input type=\"password\" name=\"password\" required></td>
                </tr>
                <tr>
                    <th></th>
                    <td><button type=\"submit\" name='login'>Sign in</button></td>  
                    </form>      
                    <form method='post'>          
                    <td><button type=\"submit\" >Disconnect</button></td> 
                    </form> 
                </tr>
              </tbody>
          </table>                        
          <a href='?action=reset-password'>forget password ?</a>
        
          <style>
         
            button{
                width: 100px;
                display:inline-block;
            }         
         
            table{
                display:inline-block;
                text-align: left;
            }
            
            th,td{
                padding-bottom: 20px; 
            }
            
          </style>
        
        ";
        if($this->http_method=='GET'){
            return $page;
        }elseif($this->http_method=='POST'){
            if (isset($_POST['login'])){
                $email=filter_var($_POST['email'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $user=Auth::authenticate($email,$_POST['password']);
            if($user==null){
                $page="your email or password is incorrect.<br><br>".$page;
                return $page;
            } else{
                Auth::loadProfile($user);
                return 'Vous êtes connecté';
                /*
                if(Auth::getActivite($email)){
                    //$url="http://localhost:63342/NetVOD/acceuil.html";
                    //return "<meta http-equiv='refresh' content='0.5;url=$url'>";

                }else{
                    setcookie("user",$email);
                    return "you have not activated this account.<br><a href='?action=activite'>activate</a>";
                }*/
            }
            } else {
                unset($_SESSION['user']);
                return 'Vous êtes déconnecté';
            }
        } else{
            return "inaccessible.";
        }
    }
}