<?php

class Dinero {
    private $monto;

    public function __construct(float $monto) {
        if ($monto < 0) {
            throw new Exception("El monto no puede ser negativo");
        }
        $this->monto = $monto;
    }

    public function sumar(Dinero $otro): Dinero {
        return new Dinero($this->monto + $otro->monto);
    }

    public function getMonto(): float {
        return $this->monto;
    }
}