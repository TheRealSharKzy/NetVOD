<?php

namespace User;

use Exception\InvalideProperty;
use http\Header;
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

    static function checkLogin() {
        if (!isset($_SESSION['user'])){
            Header('Location: ?action=sign-in');
        }
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getEnCours(): array
    {
        return $this->enCours;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ListePreference
     */
    public function getListPref(): ListePreference
    {
        return $this->listPref;
    }

    /**
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }


}