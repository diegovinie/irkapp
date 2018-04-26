<?php
/**
 * @version 0.1 09ABR18
 * @author diego.viniegra@gmail.com
 */

use Database\PDOe;
use Database\Migrations\TerceraMigracion as Mig; // migración actual

\hlp\logger("Creando la base de datos: ");
PDOe::createDatabase();
\hlp\logger("hecho.");

\hlp\logger("Creando tablas: ");
Mig::up();
\hlp\logger("hecho.");
