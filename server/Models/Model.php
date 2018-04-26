<?php
/**
 * Métodos para comunicarse con la base de datos
 *
 * @version 0.1 09ABR18
 * @author diego.viniegra@gmail.com
 */

namespace Models;

use Database\PDOe;
use PDO;

/**
 * La clase con las funcionalidades. En teoría es una clase abstracta y
 * debe ser heredada.
 */
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

    public $order;

    public $columns;
    /* Array(4) de condicionales para where */
    public $conditions;
    /* Array(4) assoc de parámetros joins */
    public $joins;
    /* Array assoc de parámetros para PDO::Execute() */
    public $parameters;
    /* La sentencia sql que se va a ejecutar */
    public $query;
    /* tipo de sentencia: insert, update, select*/
    public $queryType;
    /* La forma de extraer los resultados */
    public $retrieve = 'fetchAll';

    public function __construct($table=null)
    {
        if($table) $this->table = $table;

        // revisar $app: aparentemente no existe
        $this->db = isset($app['db'])? $app['db'] : PDOe::connect();
    }

    /**
     * Crea una consulta de una o más columnas
     *
     * @return string la consulta sql
     */
    public function find(/*string*/ ...$columns)
    {
        $this->clear();
        $this->queryType = 'select';

        // Los parámetros se pasan por back quotes por seguridad
        $bqCols = array_map('\hlp\bquote', $columns);

        $this->columns = implode($bqCols, ', ');
        \hlp\logger("columnas: $this->columns");

        $this->query = "SELECT $this->columns FROM `$this->table`";

        return $this;
    }

    public function findAll()
    {
        return $this->find('*');
    }

    /**
     * Agrega orden a la consulta
     */
    public function descend(/*string*/ $col, $limit=null)
    {
        $this->order .= " ORDER BY `$col` DESC";
        $this->order .= $limit? "LIMIT $limit" : "";

        return $this;
    }

    /**
     * Consulta para solo el primer resultado
     */
    public function findOne($columns)
    {
        // Para extraer solo el primer resultado
        $this->retrieve = 'fetch';

        return $this->find($columns);
    }

    /**
     * Crea la sentencia para un insert Prepara la instancia.
     *
     * @return Model el objeto mismo
     */
    public function insert(/*string*/ ...$args)
    {
        $this->clear();
        $this->queryType = 'insert';
        $this->query = "INSERT INTO $this->table VALUES (". implode($args, ', ') .")";

        return $this;
    }

    /**
     * Crea la sentencia para un Update. Prepara la instancia.
     *
     * @return Model el objeto mismo
     */
    public function update(/*string*/ $col, /*string*/ $value)
    {
        $this->clear();
        $this->queryType = 'update';
        $this->query = "UPDATE $this->table SET `$col` = '$value'";
        return $this;
    }

    /**
     * Devuelve el último id insertado
     */
    public function getLastId()
    {
        $res = $this->db->query("SELECT LAST_INSERT_ID()");

        return $res->fetchColumn(0);
    }

    /**
     * Prepara un where en la instancia
     *
     * @return Model el objeto mismo
     */
    public function where($col, $rel, $value)
    {
        // Lista de condicionales
        $types = array('=', '!=', '<', '>', '<=', '>=');

        // si no está en la lista manda error
        if(!in_array($rel, $types)) throw error;

        $this->conditions[] = [$col, $rel, ":$col", $value];
        $this->parameters[$col] = $value;

        return $this;
    }

    /**
     * Prepara un join en la instancia

     * @param string $type tipo de join
     * @param string $table la tabla a unir
     * @param string $map origen
     * @param string $to destino
     *
     * @return Model el objeto mismo
     */
    public function join($type, $table, $map, $to)
    {
        $this->joins[] = compact('type', 'table', 'map', 'to');

        return $this;
    }

    /**
     * Devuelve un anexo con los where a la sentencia sql
     */
    private function analizeWhere()
    {
        $wherePart = '';

        if($this->conditions){
            foreach ($this->conditions as $cond) {
                $wherePart .= $wherePart == ''? ' WHERE' : ' AND';
                // Paramétricos execute
                $wherePart .= " `$cond[0]` $cond[1] $cond[2]";
            }
        }
        return $wherePart;
    }

    /**
     * Devuelve un anexo con los join a la sentencia sql
     */
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

    /**
     * Ejecuta el query.
     */
    public function exec()
    {
        $sentence = $this->query .$this->analizeJoins() .$this->analizeWhere();
        \hlp\logger($sentence);
        $stmt = $this->db->prepare($sentence);

        // Amarre de parámetros
        if($this->parameters){
            foreach ($this->parameters as $key => $value) {
                // echo "bindParam('$key', '$value')";
                $stmt->bindParam("$key", $value);
            }
        }

        $res = $stmt->execute();

        // Revisión de los resultados según el tipo de query
        switch ($this->queryType) {
            case 'select':
                if(!$res){
                    \hlp\logger($stmt->errorInfo()[2], true);
                }
                else{
                    // var_dump($stmt->{($this->retrieve)}(PDO::FETCH_ASSOC)) ;
                    return $stmt->{($this->retrieve)}(PDO::FETCH_ASSOC);
                }
                break;

            case 'update':
                if(!$res){
                    \hlp\logger($stmt->errorInfo()[2], true);
                }
                else{
                    \hlp\logger('update correcto.');
                }
                break;

            case 'insert':
                if(!$res){
                    \hlp\logger($stmt->errorInfo()[2], true);
                }
                else{
                    \hlp\logger('insert correcto.');
                }
                break;

            default:
                die('error en queryType');
                break;
        }
    }

    /**
     * Limpia algunos atributos. Usualmente al empezar una consulta
     */
    private function clear()
    {
        $this->columns = null;
        $this->conditions = null;
        $this->parameters = null;
        $this->joins = null;
        $this->query = null;
        $this->queryType = null;
    }

    /**
     * Llamada alternativa al método where()
     * Ejem: ->whereId('=', 2)
     */
    public function __call($method, $args)
    {
        if(strpos($method, 'where') === 0){
            $col = strtolower(substr($method, 5) );

            return $this->where($col, ...$args);
        }
    }
}
