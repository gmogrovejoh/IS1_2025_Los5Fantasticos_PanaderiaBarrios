<?php
// app/config.php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'u288537394_panaderia');
define('DB_USER', 'u288537394_adminPanaderia');
define('DB_PASS', 'Deskjetsa15');

// Configuración de la aplicación
define('APP_NAME', 'Panadería Barrios');
define('BASE_URL', 'https://lavender-meerkat-667046.hostingersite.com/');

// Configuración de horarios de corte
define('HORA_CORTE_MANANA', '22:00'); // 10 PM del día anterior
define('HORA_CORTE_TARDE', '10:00');  // 10 AM del mismo día

// Configuración de sesión
session_start();
?>