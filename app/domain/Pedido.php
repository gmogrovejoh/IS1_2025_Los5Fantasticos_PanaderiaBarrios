<?php

require_once 'FechaEntrega.php';
require_once 'Dinero.php';

class Pedido {
    private $idCliente;
    private $fechaEntrega;
    private $subtotal;
    private $costoEnvio;
    private $total;

    public function __construct(
        int $idCliente,
        FechaEntrega $fechaEntrega,
        Dinero $subtotal
    ) {
        $this->idCliente = $idCliente;
        $this->fechaEntrega = $fechaEntrega;
        $this->subtotal = $subtotal;
        $this->costoEnvio = new Dinero(0);
        $this->recalcularTotal();
    }

    public function agregarCostoEnvio(Dinero $costo) {
        $this->costoEnvio = $costo;
        $this->recalcularTotal();
    }

    private function recalcularTotal() {
        $this->total = $this->subtotal->sumar($this->costoEnvio);
    }

    public function getTotal(): float {
        return $this->total->getMonto();
    }

    public function getSubtotal(): float {
        return $this->subtotal->getMonto();
    }

    public function getFechaEntrega(): string {
        return $this->fechaEntrega->getFecha();
    }
}