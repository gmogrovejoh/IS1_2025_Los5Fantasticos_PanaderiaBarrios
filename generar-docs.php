<?php
require 'vendor/autoload.php';

use OpenApi\Generator;

// Escanear SOLO el archivo de especificación que acabamos de crear
$openapi = Generator::scan(['app/OpenApiSpec.php']);

// Guardar el JSON
file_put_contents('public/swagger.json', $openapi->toJson());

echo "¡Documentación generada con éxito! Revisa public/swagger.json \n";
?>