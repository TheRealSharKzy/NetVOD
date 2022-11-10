<?php

namespace Action;

class AcceuilAction extends Action
{
    public function execute(): string
    {
        $list_cours = new ListAction('EnCours');
        $list_prefere = new ListAction('Prefere');
        $list_visionne = new ListAction('Visionne');

        return $list_cours->execute() . $list_prefere->execute() . $list_visionne->execute();

    }
}