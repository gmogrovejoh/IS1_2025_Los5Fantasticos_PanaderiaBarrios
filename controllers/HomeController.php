<?php
class HomeController {
    
    public function home() {
        $productoModel = new Producto();
        $categoriaModel = new Categoria();
        
        $productos_destacados = array_slice($productoModel->obtenerTodos(), 0, 8);
        $categorias = $categoriaModel->obtenerTodas();
        
        include 'views/home/index.php';
    }
    
    public function about() {
        include 'views/home/about.php';
    }
    
    public function contact() {
        include 'views/home/contact.php';
    }
}
?>