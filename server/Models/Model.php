<?php

namespace Models;

use Database\PDOe;
use PDO;

class Model
{
    /**
     * Nombre de la tabla
     *
     * @var string
     */
    protected $table;

    /**
     * Instancia de la Base de datos
     *
     * @var PDOe
     */
    private $db;

    public $columns;
    public $conditions;
    public $joins;
    public $parameters;
    public $query;
    public $queryType;
    public $retrieve = 'fetchAll';

    public function __construct($table=null)
    {
        if($table) $this->table = $table;

        $this->db = isset($app['db'])? $app['db'] : PDOe::connect();
    }

    public function find(...$columns)
    {
        $this->clear();
        $this->queryType = 'select';

        $bqCols = array_map('\hlp\bquote', $columns);

        $this->columns = implode($bqCols, ', ');
        echo $this->columns;

        $this->query = "SELECT $this->columns FROM `$this->table`";

        return $this;
    }

    public function findAll()
    {
        return $this->find('*');
    }

    public function findOne($columns)
    {
        $this->retrieve = 'fetch';

        return $this->find($columns);
    }

    public function insert(...$args)
    {
        $this->clear();
        $this->queryType = 'insert';
        $this->query = "INSERT INTO $this->table VALUES (". implode($args, ', ') .")";

        return $this;
    }

    public function update($col, $value)
    {
        $this->clear();
        $this->queryType = 'update';
        $this->query = "UPDATE $this->table SET `$col` = '$value'";
        return $this;
    }

    public function getLastId()
    {
        $res = $this->db->query("SELECT LAST_INSERT_ID()");

        return $res->fetchColumn(0);
    }

    public function where($col, $rel, $value)
    {
        $types = array('=', '!=', '<', '>', '<=', '>=');
        if(!in_array($rel, $types)) throw error;

        $this->conditions[] = [$col, $rel, ":$col", $value];
        $this->parameters[$col] = $value;

        return $this;
    }

    public function join($type, $table, $map, $to)
    {
        $this->joins[] = compact('type', 'table', 'map', 'to');

        return $this;
    }

    private function analizeWhere()
    {
        $wherePart = '';

        if($this->conditions){
            foreach ($this->conditions as $cond) {
                $wherePart .= $wherePart == ''? ' WHERE' : ' AND';
                $wherePart .= " `$cond[0]` $cond[1] $cond[2]";
            }
        }
        return $wherePart;
    }

    private function analizeJoins()
    {
        $joinPart = '';

        if($this->joins){
            foreach ($this->joins as $join) {
                $joinPart .= " {$join['type']} JOIN `{$join['table']}`";
                $joinPart .= " ON `$this->table`.`{$join['map']}` = `{$join['table']}`.`{$join['to']}`";
            }
        }
        return $joinPart;
    }

    public function exec()
    {
        echo $this->query .$this->analizeJoins() .$this->analizeWhere();
        $stmt = $this->db->prepare($this->query .$this->analizeJoins() .$this->analizeWhere() );

        if($this->parameters){
            foreach ($this->parameters as $key => $value) {
                // echo "bindParam('$key', '$value')";
                $stmt->bindParam("$key", $value);
            }
        }

        $res = $stmt->execute();
        echo "\n";

        switch ($this->queryType) {
            case 'select':
                if(!$res){
                    echo $stmt->errorInfo()[2];
                }
                else{
                    // var_dump($stmt->{($this->retrieve)}(PDO::FETCH_ASSOC)) ;
                    return $stmt->{($this->retrieve)}(PDO::FETCH_ASSOC);
                }
                break;

            case 'update':
                if(!$res){
                    \hlp\logger($stmt->errorInfo()[2]);
                }
                else{
                    \hlp\logger('update correcto.', true);
                }
                break;

            case 'insert':
                if(!$res){
                    \hlp\logger($stmt->errorInfo()[2]);
                }
                else{
                    \hlp\logger('insert correcto.', true);
                }
                break;

            default:
                die('error en queryType');
                break;
        }

    }

    private function clear()
    {
        $this->columns = null;
        $this->conditions = null;
        $this->joins = null;
        $this->query = null;
        $this->queryType = null;
    }

    public function __call($method, $args)
    {
        if(strpos($method, 'where') === 0){
            $col = strtolower(substr($method, 5) );

            return $this->where($col, ...$args);
        }
    }
}
