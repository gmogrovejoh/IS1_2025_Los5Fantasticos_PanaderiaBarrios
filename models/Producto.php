<?php
class Producto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerTodos() {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM producto p 
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
                WHERE p.disponible = 'si'
                ORDER BY p.nombre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function obtenerPorCategoria($id_categoria) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM producto p 
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
                WHERE p.id_categoria = :id_categoria AND p.disponible = 'si'
                ORDER BY p.nombre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_categoria', $id_categoria);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function obtenerPorId($id) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM producto p 
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
                WHERE p.id_producto = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function buscar($termino) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM producto p 
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
                WHERE (p.nombre LIKE :termino OR p.descripcion LIKE :termino) 
                AND p.disponible = 'si'
                ORDER BY p.nombre";
        
        $stmt = $this->db->prepare($sql);
        $termino = "%{$termino}%";
        $stmt->bindParam(':termino', $termino);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>