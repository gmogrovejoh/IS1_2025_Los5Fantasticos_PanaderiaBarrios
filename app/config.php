<?php
// app/config.php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'PanaderiaBarriosDB');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración de la aplicación
define('APP_NAME', 'Panadería Barrios');
define('BASE_URL', 'http://localhost/IS1_2025_LOS5FANTASTICOS_PANADERIABARRIOS/');

// Configuración de horarios de corte
define('HORA_CORTE_MANANA', '22:00'); // 10 PM del día anterior
define('HORA_CORTE_TARDE', '10:00');  // 10 AM del mismo día

// Configuración de sesión
session_start();
?>