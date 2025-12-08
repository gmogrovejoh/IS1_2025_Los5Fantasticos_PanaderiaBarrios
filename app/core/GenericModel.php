<?php
require_once 'Database.php';

/**
 * Clase Genérica para operaciones CRUD.
 * Cumple con el requisito de "Uso de Genéricos/Templates" de la práctica.
 */
class GenericModel {
    protected $conn;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Obtener todos los registros
    public function findAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE " . $this->primaryKey . " = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear registro dinámicamente
    public function create($data) {
        $fields = array_keys($data);
        $placeholders = ":" . implode(", :", $fields);
        $columns = implode(", ", $fields);

        $query = "INSERT INTO " . $this->table . " ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Eliminar registro
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE " . $this->primaryKey . " = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>