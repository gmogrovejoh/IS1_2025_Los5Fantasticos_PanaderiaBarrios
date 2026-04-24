<?php

class TipoEntrega {
    private $tipo;

    private const DOMICILIO = 'DOMICILIO';
    private const RECOJO = 'RECOJO';

    public function __construct(string $tipo) {
        $tiposValidos = [self::DOMICILIO, self::RECOJO];

        if (!in_array($tipo, $tiposValidos)) {
            throw new Exception("Tipo de entrega inválido");
        }

        $this->tipo = $tipo;
    }

    public function esDomicilio(): bool {
        return $this->tipo === self::DOMICILIO;
    }

    public function getTipo(): string {
        return $this->tipo;
    }
}