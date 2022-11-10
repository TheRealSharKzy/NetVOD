<?php

namespace User;

use Catalogue\list\ListePreference;
use Exception\InvalideProperty;


class User
{

    private int $id;
    private string $pseudo,$email;
    private array $enCours;
    private ListePreference $listPref;

    public function __construct(int $id,string $pseudo,$email)
    {
        $this->id=$id;
        $this->email=$email;
        $this->pseudo=$pseudo;
        $this->listPref = new ListePreference();
    }

    public function &__get($name)
    {
        if(property_exists($this,$name))return $this->$name;
        else throw new InvalideProperty();
    }

    static function checkLogin() {
        if (!isset($_SESSION['user'])){
            Header('Location: ?action=sign-in');
        }
    }

    public function __set(string $name, $value): void
    {
        // TODO: Implement __set() method.
        if(property_exists($this,$name))$this->$name=$value;
        else throw new InvalideProperty();
    }

}