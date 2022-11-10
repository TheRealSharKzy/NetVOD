<?php

namespace Action;

use Auth\Auth;

class SigninAction extends Action
{

    public function execute(): string
    {
        $page = "<table>
                <tbody>
               <form method='post'>          
                <tr>
                    <th><label>Email:</label></th>
                    <td><input type=\"email\" name=\"email\" required></td>
                </tr>
                <tr>
                    <th><label>Mot de passe:</label></th>
                    <td> <input type=\"password\" name=\"password\" required></td>
                </tr>
                <tr>
                    <th></th>
                    <td><button type=\"submit\" name='login'>Se connecter</button></td>  
                    </form>                          
                </tr>
              </tbody>
          </table>                        
          <a href='?action=reset-password'>Mot de passe oublié ?</a>
        
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
        if ($this->http_method == 'GET') {
            return $page;
        } elseif ($this->http_method == 'POST') {//si un client a fait entrer ses email et mot de passe
                $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
                $user = Auth::authenticate($email, $_POST['password']);
                if ($user == null) {//si l'email ou mot de passe ne marchent pas
                    $page = "your email or password is incorrect.<br><br>" . $page;
                    return $page;
                } else {
                    if (Auth::getActive($email)) {//si le compte est activé
                        //connection passe
                        Auth::loadProfile($user);
                        Header('Location: ?action=acceuil');
                    } else {
                        //aller activer
                        setcookie("user", $email);
                        return "you have not activated this account.<br><a href='?action=active'>activate</a>";
                    }
                }
    } else{
            return "inaccessible.";
        }
    }
}