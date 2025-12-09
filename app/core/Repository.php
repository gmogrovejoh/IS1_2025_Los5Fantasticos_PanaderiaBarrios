<?php

class Repository {
    protected $conn;
    protected $table;

    public function __construct($table) {
        $db = new Database();
        $this->conn = $db->connect();
        $this->table = $table;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($data) {
        $cols = implode(",", array_keys($data));
        $vals = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO {$this->table} ($cols) VALUES ($vals)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function actualizar($id, $data) {
        $sets = [];
        foreach ($data as $key => $value) {
            $sets[] = "$key = :$key";
        }
        $setString = implode(", ", $sets);

        $query = "UPDATE {$this->table} SET $setString WHERE id = :id";
        $data["id"] = $id;

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function eliminar($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>