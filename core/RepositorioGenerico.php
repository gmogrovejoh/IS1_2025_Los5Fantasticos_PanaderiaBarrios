<?php

class RepositorioGenerico {
    private $conexion;
    private $tabla;
    private $idCampo;

    public function __construct($conexion, $tabla, $idCampo) {
        $this->conexion = $conexion;
        $this->tabla = $tabla;
        $this->idCampo = $idCampo;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM {$this->tabla} ORDER BY {$this->idCampo} DESC";
        return $this->conexion->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM {$this->tabla} WHERE {$this->idCampo} = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function crear($data) {
        $columnas = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $tipos = $this->obtenerTipos($data);
        $valores = array_values($data);

        $stmt = $this->conexion->prepare("INSERT INTO {$this->tabla} ($columnas) VALUES ($placeholders)");
        $stmt->bind_param($tipos, ...$valores);

        return $stmt->execute() ? $this->conexion->insert_id : false;
    }

    public function actualizar($id, $data) {
        $set = implode(" = ?, ", array_keys($data)) . " = ?";
        $tipos = $this->obtenerTipos($data) . "i";
        $valores = array_values($data);
        $valores[] = $id;

        $stmt = $this->conexion->prepare("UPDATE {$this->tabla} SET $set WHERE {$this->idCampo} = ?");
        $stmt->bind_param($tipos, ...$valores);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conexion->prepare("DELETE FROM {$this->tabla} WHERE {$this->idCampo} = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    private function obtenerTipos($data) {
        $tipos = "";
        foreach ($data as $valor) {
            if (is_int($valor)) $tipos .= "i";
            else if (is_float($valor)) $tipos .= "d";
            else $tipos .= "s";
        }
        return $tipos;
    }
}