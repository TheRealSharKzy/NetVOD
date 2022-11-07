<?php

namespace Dispatch;

use Action\InscriptAction;
use Action\SigninAction;

class Dispatch
{
    public static function run(string $action){
        switch ($action){
            case "inscript":$ac=new InscriptAction();break;
            case "sign-in":$ac=new SigninAction();break;
        }
        echo $ac->execute();
    }
}