<?php

class ProductoApiController {

    private $productoModel;

    public function __construct() {
        $this->productoModel = new Producto();
    }

    public function obtenerTodos() {
        $data = $this->productoModel->obtenerTodos();
        echo json_encode($data);
    }

    public function obtenerPorId($id) {
        $data = $this->productoModel->obtenerPorId($id);

        if ($data) {
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Producto no encontrado"]);
        }
    }
}
