<?php
/**
 * @version 0.1 09ABR18
 * @author diego.viniegra@gmail.com
 */

switch ($argv[2]) {
    // Inicializar la base de datos
    case 'init':
        include_once 'bin/database/init.php';
        break;

    default:
        # code...
        break;
}
