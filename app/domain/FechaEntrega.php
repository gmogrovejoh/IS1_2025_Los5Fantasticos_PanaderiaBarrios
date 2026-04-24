<?php

class FechaEntrega {
    private $fecha;

    public function __construct(string $fecha) {
        $fechaMinima = new DateTime('+1 day');
        $fechaIngresada = new DateTime($fecha);

        if ($fechaIngresada < $fechaMinima) {
            throw new Exception("La fecha debe ser al menos mañana.");
        }

        $this->fecha = $fechaIngresada;
    }

    public function getFecha(): string {
        return $this->fecha->format('Y-m-d');
    }
}
