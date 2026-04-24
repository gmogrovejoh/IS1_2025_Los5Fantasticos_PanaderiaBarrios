<?php

require_once 'Dinero.php';

class Carrito {
    private $idCliente;
    private $items = [];

    public function __construct(int $idCliente) {
        $this->idCliente = $idCliente;
    }

    public function agregarItem(float $precio, int $cantidad) {
        if ($cantidad <= 0) {
            throw new Exception("Cantidad inválida");
        }

        $this->items[] = [
            'precio' => new Dinero($precio),
            'cantidad' => $cantidad
        ];
    }

    public function calcularTotal(): Dinero {
        $total = new Dinero(0);

        foreach ($this->items as $item) {
            $subtotalItem = new Dinero(
                $item['precio']->getMonto() * $item['cantidad']
            );

            $total = $total->sumar($subtotalItem);
        }

        return $total;
    }
}