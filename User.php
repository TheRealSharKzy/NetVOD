<?php

require_once 'Exception/InvalideProperty.php';

use Exception\InvalideProperty;

class User
{

    private int $id;
    private string $pseudo,$email;

    public function __construct(int $id,string $pseudo,$email)
    {
        $this->id=$id;$this->email=$email;$this->pseudo=$pseudo;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        if(property_exists($this,$name))return $this->$name;
        else throw new InvalideProperty();
    }

}