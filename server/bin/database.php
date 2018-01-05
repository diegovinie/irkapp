<?php

switch ($argv[2]) {
    case 'init':
        include_once 'bin/database/init.php';
        break;

    default:
        # code...
        break;
}
