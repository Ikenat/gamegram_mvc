<?php
//constantes
define('TYPE_GET_ALL', 'all');
define('TYPE_GET_FIRST', 'first');
define('TYPE_GET_COUNT', 'count');
define('TYPE_GET', [TYPE_GET_ALL,TYPE_GET_COUNT,TYPE_GET_FIRST]);

class ORM //Object Relationship Management = interface avec la bdd
{
    //propiéte
    public $existInBDD = false; // Permet de savoir si une entrée donnée existe

    private $db;
    private $sql;
    private $query;
    private $table;
    private $fields = [];
    private $whereFieldsAndValues = [];
    private $typewhere = ' AND ';

    private $orderFieldAndDirection;

    private $insertFieldAndValue;
    private $UpdateFieldsAndValues;
    //ex : dans Family.php
    //$this->addInsertFields('name', $name, PDO::PARAM_INT);
    //$this->launch(); Regarder du coté exec
    private $limit;
    private $offset;


    //doit me permettre de me connecter à ma base de données(constructeur)
    public function __construct()
    {
        try {
            $this->db = new PDO("mysql:host=" . BDD_HOST . ";dbname=" . BDD_NAME . ";charset=utf8", BDD_USER, BDD_PASS);
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
        $this->resetPropertiesSQL();
    }

    //on remet à zéro les propriétés qui permettent de créer la requête sql;
    private function resetPropertiesSQL()
    {
        $this->existInBDD = false;
        $this->fields = [];
        $this->whereFieldsAndValues = [];
        $this->typewhere = ' AND ';
        $this->orderFieldAndDirection = [];
        $this->insertFieldAndValue = [];
        $this->UpdateFieldsAndValues = [];
        unset($this->limit);
        unset($this->offset);
        unset($this->sql);
    }

    //doit me permettre d'executer des requête

    private function execute()
    {
        //ON construit la requête
        $this->buildSelectSQL();
        $this->query = $this->db->prepare($this->sql);
        //var_dump($this->whereFieldsAndValues);
        foreach ($this->whereFieldsAndValues as $condition) {
            $this->query->bindValue(':' . $condition['binder'], $condition['value'], $condition['type']);
        }
        if(!$this->query->execute()) {
            die('Erreur [ORM 002] : ' . $this->query->errorInfo()[2]);
        }

        //On remet à zéro les proriétés qui permettent de créer la requête SQL
        $this->resetPropertiesSQL();

    }

    public function insert()
    {
        $this->InsertSql();

        $this->query = $this->db->prepare($this->sql);

        foreach ($this->insertFieldAndValue as $condition) {
            $conditionValue = '"' . $condition['value'] . '"';
            $this->query->bindValue(':' . $condition['bind'], $conditionValue);
        }
        if(!$this->query->execute()) {
            die('Erreur [ORM 003] : ' . $this->query->errorInfo()[2]);
        }

        //On remet à zéro les proriétés qui permettent de créer la requête SQL
        $this->resetPropertiesSQL();

        $this->getLastId();

    }

    public function delete()
    {
        $this->buildDeleteSQL();
        $this->query = $this->db->prepare($this->sql);
        if(empty($this->whereFieldsAndValues)) {
            die('Erreur [ORM 005] : Il faut au moins une condition');
        }
        foreach ($this->whereFieldsAndValues as $condition) {
            $this->query->bindValue(':' . $condition['binder'], $condition['value'], $condition['type']);
        }
        if(!$this->query->execute()) {
            die('Erreur [ORM 004] : ' . $this->query->errorInfo()[2]);
        }

        //On remet à zéro les proriétés qui permettent de créer la requête SQL
        $this->resetPropertiesSQL();
    }

    public function update()
    {
        $this->buildUpdateSQL();
        $this->query = $this->db->prepare($this->sql);
        foreach ($this->whereFieldsAndValues as $condition) {
            $this->query->bindValue(':' . $condition['binder'], $condition['value'], $condition['type']);
        }
        if(!$this->query->execute()) {
            die('Erreur [ORM 006] : ' . $this->query->errorInfo()[2]);
        }

        //On remet à zéro les proriétés qui permettent de créer la requête SQL
        $this->resetPropertiesSQL();
    }

    public function getLastId()
    {
        $this->addOrder('id', 'DESC');
        $this->setSelectFields('id');
        //$this->addWhereFields('name', $value, "=",PDO::PARAM_STR);
        return $this->get('first')['id'];


    }

    public function get($type)
    {
        if (!in_array($type, TYPE_GET)) {
            die('Erreur [ORM 001] : Mauvais type pour get');
        }
        $this->execute();
        //gestion des erreurs
        switch ($type) {
            case TYPE_GET_ALL:
                return $this->query->fetchAll(PDO::FETCH_CLASS);
                break;
            case TYPE_GET_FIRST:
                return $this->query->fetch();
                break;
            case TYPE_GET_COUNT:
                return $this->query->rowCount();
                break;
        }
    }
    //doit me permettre d'extraire le résultat de ces requêtes


    public function setTable($table)
    {
        $this->table = $table;
    }
    public function setColonne($colonne)
    {
        $this->colonne = $colonne;
    }
    public function setSelectFields()
    {
        $this->fields = func_get_args();
        //func_get_args
    }
    public function setTypeWhere($typewhere)
    {
        $this->typewhere = $typewhere;
    }

    public function addWhereFields($field, $value, $operator = '=', $type = PDO::PARAM_INT)
    {
        // ICI
        $this->whereFieldsAndValues [] = [
            'field' => $field,
            'value' => $value,
            'operator' => $operator,
            'type' => $type
        ];
    }
    public function addUpdateField($field, $value, $operator = '=', $type = PDO::PARAM_INT)
    {
        // ICI
        $this->UpdateFieldsAndValues [] = [
            'field' => $field,
            'bind' =>  $field,
            'value' => $value,
            'operator' => $operator,
            'type' => $type
        ];
    }

    private function buildSelectSQL($whereCondition = '')
    {
        $sql ='SELECT ';

        if (empty($this->fields)) {
            $sql .= '*';
        }else {
            $sql .= implode(', ', $this->fields);
        }
        $sql .= ' FROM ' . $this->table;

        $sql.= $this->handleWhere();
        $sql .= $this->handleOrder();

        if (isset($this->limit)) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if (isset($this->offset)) {
            $sql .= ' OFFSET ' . $this->limit;
        }

        $this->sql = $sql;
    }

    private function buildDeleteSQL()
    {
        $sql ='DELETE ';
        $sql .= ' FROM ' . $this->table;

        $sql.= $this->handleWhere();

        $this->sql = $sql;

    }
    private function buildUpdateSQL()
    {
        $sql ='UPDATE ' . $this->table;
        $sql.= $this->handleSet();

        $sql.= $this->handleWhere();
        echo $sql;
        $this->sql = $sql;

    }

    private function InsertSql()
    {
        $sql = 'INSERT INTO ' . $this->table;
        //champs
        $sql .= ' ( ';
        $sql.= implode(',', array_column($this->insertFieldAndValue, 'field'));
        $sql.= ') VALUES (';

        //Valeur
        $sql.= '"' . implode('","', array_column($this->insertFieldAndValue, 'value'));
        $sql.= '")';

        $this->sql = $sql;
        echo $sql;
    }


    public function addOrder($field, $direction = 'ASC')
    {
        $this->orderFieldAndDirection [] = [
            'field' => $field,
            'direction' => $direction
        ];
    }
    public function handleOrder()
    {
        if(empty($this->orderFieldAndDirection)) {
            return '';
        }

        $orders = [];
        foreach ($this->orderFieldAndDirection as $oFaD) {
            $orders [] = $oFaD['field'] . ' ' . $oFaD['direction'];
        }

        return ' ORDER BY ' . implode(', ', $orders);
    }

    private function handleSet()
    {
        if(empty($this->UpdateFieldsAndValues)) {
            die();
        }
        $set = [];
        foreach ($this->UpdateFieldsAndValues as $uFaV) {
            $set [] = $uFaV['field'] . ' ' . $uFaV['operator'] . ' "' . $uFaV['value'] . '"';
        }

        return ' SET ' . implode(', ', $set);
    }

    public function addInsertFields($field, $value, $type = PDO::PARAM_STR)
    {
        $this->insertFieldAndValue [] = [
            'field' => '`' . $field . '`',
            'bind' =>  $field,
            'value' => $value,
            'type' =>  $type
        ];
    }

    private function handleWhere()
    {
        if(empty($this->whereFieldsAndValues)) {
            return '';
        }
            // ['field' => 'id', 'value' => 14, 'operator' => '=', 'type' => INT]
            // id = :id;
            $wheres = [];
            $binders = [];
            foreach ($this->whereFieldsAndValues as $id => $condition) {

                $binder = $condition['field'];
                $nb = 2;
                while(in_array($binder, $binders)) {
                    $binder = $condition['field'] . '_' . $nb;
                    $nb++;
                }

                $binders[] = $binder;
                $wheres [] = $condition['field'] . ' ' . $condition['operator'] . ' :' . $binder;
                $this->whereFieldsAndValues[$id]['binder'] = $binder;

            }

            return ' WHERE ' . implode(' ' . $this->typewhere . ' ', $wheres) . ' ';
    }
    //Méthode d'accès rapide au donnée
    public function getById($id)
    {
        $this->addWhereFields('id', $id);
        return $this->get('first');
    }

    //on vérifie que l'élément $id existe
    public function existInBDD($id) {
        $this->addWhereFields('id', $id);
        $this->setSelectFields('id');
        $this->existInBDD = (bool) $this->get('count');
        return $this->existInBDD;
    }

        //Je garnis mon objet avec des propriétés qui correspondent aux noms de mes chammps avec les valeurs associés à l'id
    public function populate($id)
    {
        //Vérifié l'existance
        if (!$this->existInBDD($id)) {
            return false;
        }

        //On va chercher les données
        $model = $this->getById($id);

        foreach ($model as $field => $value) {
            if (is_numeric($field)) {
                continue;
            }

            $this->$field = $value; //Attribution dynamique
            //PHP est permissif à ce niveau là et permet ça
        }

        return true;
    }

    public function exist() {
        return $this->existInBDD;
    }

    public function setLimit($nb, $offSet = null)
    {
        $this->limit = $nb;
        if (isset($offSet)) {
            $this->offset = $offSet;
        }
    }

}