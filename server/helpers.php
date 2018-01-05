<?php
namespace hlp;

function logger($string, $debug=false){

    if($debug){

        if(DEBUG == true){
            echo $string;
            echo "\n";
        }
    }
    else{
        echo $string;
        echo "\n";
    }
}

function bquote($item){
    return "`".$item."`";
}

/**
 * Convierte un archivo .csv en un array
 *
 * @param resource $handle el manejador del archivo
 * @return array
 */
function convertCsvArray(/*resource*/ $handle){

    $keys = fgetcsv($handle);
    $data = array();

    while(!feof($handle)){
        $values = fgetcsv($handle);
        $i = 0;
        $row = array();
        if(!$values) break;

        for($i = 0; $i < count($keys); $i++){

            $row[$keys[$i]] = $values[$i];
        }
        $data[] = $row;
    }

    return $data;
}
