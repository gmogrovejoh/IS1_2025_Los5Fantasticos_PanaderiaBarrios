<?php

class IdCliente {
    private $id;

    public function __construct(int $id) {
        if ($id <= 0) {
            throw new Exception("ID de cliente inválido");
        }
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }
}