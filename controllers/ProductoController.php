<?php
class ProductoController {
    
    public function catalogo() {
        $productoModel = new Producto();
        $categoriaModel = new Categoria();
        
        $id_categoria = $_GET['categoria'] ?? null;
        $busqueda = $_GET['buscar'] ?? null;
        
        if ($busqueda) {
            $productos = $productoModel->buscar($busqueda);
            $titulo = "Resultados para: " . htmlspecialchars($busqueda);
        } elseif ($id_categoria) {
            $productos = $productoModel->obtenerPorCategoria($id_categoria);
            $categoria = $categoriaModel->obtenerPorId($id_categoria);
            $titulo = "Categoría: " . ($categoria['nombre'] ?? 'Desconocida');
        } else {
            $productos = $productoModel->obtenerTodos();
            $titulo = "Todos los productos";
        }
        
        $categorias = $categoriaModel->obtenerTodas();
        
        include 'views/productos/catalogo.php';
    }
    
    public function detalle() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=producto&action=catalogo');
            exit;
        }
        
        $productoModel = new Producto();
        $producto = $productoModel->obtenerPorId($id);
        
        if (!$producto) {
            header('Location: index.php?controller=producto&action=catalogo');
            exit;
        }
        
        include 'views/productos/detalle.php';
    }
}
?>