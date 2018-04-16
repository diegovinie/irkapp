<?php
/**
 * Funciones de ayuda
 *
 * @version 0.1 09ABR18
 * @author diego.viniegra@gmail.com
 */

namespace hlp;

/**
 * Registra salida (incompleta)
 *
 * @param string $string la cadena a registrar
 * @param bool $debug activa el registro
 */
function logger($string, $debug=false){

    if($debug){
        // Verifica si la depuración es activa en configuración
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

    $data = array();
    $keys = fgetcsv($handle);

    while(!feof($handle)){

        $values = fgetcsv($handle);
        $row = array();
        $i = 0;

        if(!$values) break;

        for($i = 0; $i < count($keys); $i++){

            $row[$keys[$i]] = $values[$i];
        }
        $data[] = $row;
    }

    return $data;
}
