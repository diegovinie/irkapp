<?php

namespace Database;

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

    static function create($name, $callback)
    {
        $squema = new Schema($name);

        $callback($squema);

        $db = PDOe::connect();

        $squema->execute($db);
    }

    public function dropIfExists($name)
    {
        $db = PDOe::connect();

        $ex = $db->exec(
            "SET FOREIGN_KEY_CHECKS=0;
            DROP TABLE IF EXISTS $name CASCADE;
            SET FOREIGN_KEY_CHECKS=1;"
        );
    }

    static function parseOptions(/*array*/ $options)
    {
        $stmt = '';

        $stmt .= isset($options['attribute']) ? " {$options['attribute']}" : '';
        $stmt .= isset($options['default'])  ? ' DEFAULT ' .$options['default'] : ' NOT NULL';
        $stmt .= isset($options['increment'])? ' AUTO_INCREMENT' : '';
        $stmt .= isset($options['comment'])  ? " COMMENT '" .$options['comment'] ."'": '';

        return $stmt;
    }

    public function int(string $name, /*int*/ $size=11, /*array*/ $options=null)
    {
        $stmt = "`$name` int($size)" .Schema::parseOptions($options);

        $this->addToColumn($stmt);
    }

    public function increment($name, $size=11)
    {
        $this->int($name, $size, [
            'increment' => true,
            'attribute' => 'unsigned'
            ]
        );
    }

    public function string(/*string*/ $name, /*int*/ $length=256, /*array*/ $options=null)
    {
        $stmt = "`$name` varchar($length)" .Schema::parseOptions($options);

        $this->addToColumn($stmt);
    }

    public function timestamp($name, $update=false)
    {
        $stmt = "`$name` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP";
        $stmt .= $update? ' ON UPDATE CURRENT_TIMESTAMP': '';
        $this->addToColumn($stmt);
    }

    public function timestamps()
    {
        $this->timestamp('created');
        $this->timestamp('updated', true);
    }

    public function bool($name, $options=null)
    {
        $stmt = "`$name` BOOLEAN NOT NULL" .Schema::parseOptions($options);

        $this->addToColumn($stmt);
    }

    public function comment($string)
    {
        $this->comment = "COMMENT='$string'";
    }

    public function primaryKey($name)
    {
        $this->keys = "PRIMARY KEY (`$name`)";
    }

    public function unique($col)
    {
        $this->keys .= ", UNIQUE `$col` (`$col`)";
    }

    private function addToColumn(/*string*/ $stmt)
    {
        $this->columns .= ($this->columns != ''? ', ' : '') .$stmt;
    }

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
