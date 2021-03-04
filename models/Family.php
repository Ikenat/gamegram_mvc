<?php

class Family extends ORM
{
    public function __construct($id = null)
    {
        parent::__construct();
        $this->setTable('families');

        if ($id != null) {
            $this->populate($id);
        }
    }

    public function create($name)
    {
        $this->addInsertFields('name', $name, PDO::PARAM_INT);
        //echo $family->name doit afficher RPG et family->id => le nouvel id
        //Retroune le nouvel id crée
        //s'inspirer de get()
        $newId = $this->insert($name);//PDO::exec
        $this->populate($newId);
    }
}