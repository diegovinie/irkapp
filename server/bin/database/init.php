<?php

use Database\PDOe;
use Database\Migrations\PrimeraMigracion as Mig;

\hlp\logger("Creando la base de datos: ");
PDOe::createDatabase();
\hlp\logger("hecho.");

\hlp\logger("Creando tablas: ");
Mig::up();
\hlp\logger("hecho.");
