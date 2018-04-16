<?php
/**
 * @version 0.1 09ABR18
 * @author diego.viniegra@gmail.com
 */

namespace Database;

/**
 * El esquema para crear una nueva tabla
 */
class Schema
{
    protected $columns = '';
    protected $keys = '';
    protected $name;
    protected $comment;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Tiene que ver con migraciones. revisar
     */
    static function create($name, $callback)
    {
        $squema = new Schema($name);

        $callback($squema);

        $db = PDOe::connect();

        $squema->execute($db);
    }

    /**
     * Elimina la tabla si existe
     */
    public function dropIfExists(/*string */ $name)
    {
        $db = PDOe::connect();

        $ex = $db->exec(
            "SET FOREIGN_KEY_CHECKS=0;
            DROP TABLE IF EXISTS $name CASCADE;
            SET FOREIGN_KEY_CHECKS=1;"
        );
    }

    /**
     * Prepara las opciones para agregar en columna
     *
     * @return string las opciones
     */
    static function parseOptions(/*array*/ $options)
    {
        $stmt = '';

        $stmt .= isset($options['attribute']) ? " {$options['attribute']}" : '';
        $stmt .= isset($options['default'])  ? ' DEFAULT ' .$options['default'] : ' NOT NULL';
        $stmt .= isset($options['increment'])? ' AUTO_INCREMENT' : '';
        $stmt .= isset($options['comment'])  ? " COMMENT '" .$options['comment'] ."'": '';

        return $stmt;
    }

    /**
     * Prepara una columna tipo int
     */
    public function int(string $name, /*int*/ $size=11, /*array*/ $options=null)
    {
        $stmt = "`$name` int($size)" .Schema::parseOptions($options);

        $this->addToColumn($stmt);
    }

    /**
     * Prepara una columna tipo int con autoincremento y sin signo
     */
    public function increment($name, $size=11)
    {
        $this->int($name, $size, [
            'increment' => true,
            'attribute' => 'unsigned'
            ]
        );
    }

    /**
     * Prepara una columna tipo varchar
     */
    public function string(/*string*/ $name, /*int*/ $length=256, /*array*/ $options=null)
    {
        $stmt = "`$name` varchar($length)" .Schema::parseOptions($options);

        $this->addToColumn($stmt);
    }

    /**
     * Prepara una columna tipo timestamp
     */
    public function timestamp(/*string*/ $name, $update=false)
    {
        $stmt = "`$name` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP";
        $stmt .= $update? ' ON UPDATE CURRENT_TIMESTAMP': '';
        $this->addToColumn($stmt);
    }

    /**
     * Prepara dos columnas tipo timestamp de creación y actualización
     */
    public function timestamps()
    {
        $this->timestamp('created');
        $this->timestamp('updated', true);
    }

    /**
     * Prepara una columna tipo boolean
     */
    public function bool(/*string*/ $name, $options=null)
    {
        $stmt = "`$name` BOOLEAN NOT NULL" .Schema::parseOptions($options);

        $this->addToColumn($stmt);
    }

    /**
     * Prepara el comentario de la tabla
     */
    public function comment($string)
    {
        $this->comment = "COMMENT='$string'";
    }

    /**
     * Prepara la llave primaria
     */
    public function primaryKey($name)
    {
        $this->keys = "PRIMARY KEY (`$name`)";
    }

    /**
     * Prepara una llave única
     */
    public function unique($col)
    {
        $this->keys .= ", UNIQUE `$col` (`$col`)";
    }

    /**
     * Agrega una columna a la sentencia sql
     */
    private function addToColumn(/*string*/ $stmt)
    {
        // Revisar
        $this->columns .= ($this->columns != ''? ', ' : '') .$stmt;
    }

    /**
     * Ejecuta la creación de una tabla
     */
    private function execute(PDOe $db, $testing=false)
    {
        $stmt = "CREATE TABLE $this->name ($this->columns, $this->keys) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci $this->comment";

        \hlp\logger($stmt, true);

        if(!$testing){
            $ex = $db->exec(
                "SET FOREIGN_KEY_CHECKS=0;
                DROP TABLE IF EXISTS $this->name CASCADE;
                SET FOREIGN_KEY_CHECKS=1;"
            );

            $ex2 = $db->exec($stmt);

            if(!$ex2){
                \hlp\logger($db->errorInfo()[2] );
            }
        }
    }
}
