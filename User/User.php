<?php

namespace User;

require_once 'Exception/InvalideProperty.php';

use Exception\InvalideProperty;
use list\ListePreference;

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
    }

    public function __get($name)
    {
        if(property_exists($this,$name))return $this->$name;
        else throw new InvalideProperty();
    }

}