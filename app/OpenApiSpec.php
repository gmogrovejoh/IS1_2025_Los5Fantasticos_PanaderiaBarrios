<?php
/**
 * ESTE ARCHIVO ES SOLO PARA GENERAR LA DOCUMENTACIÓN SWAGGER
 * No afecta el funcionamiento real de tu aplicación.
 */

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      title="API Panadería Barrios", 
 *      version="1.0.0",
 *      description="Documentación de los servicios REST para la integración B2B/B2C.",
 *      @OA\Contact(
 *          email="soporte@panaderiabarrios.com"
 *      )
 * )
 * @OA\Server(
 *      url="http://localhost/IS1_2025_LOS5FANTASTICOS_PANADERIABARRIOS/public",
 *      description="Servidor Local"
 * )
 */
class OpenApiSpec {

    /**
     * @OA\Get(
     *     path="/ApiProducto/index",
     *     summary="Obtener catálogo de productos",
     *     description="Retorna la lista completa de productos disponibles para venta.",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_producto", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Pan Baguette"),
     *                     @OA\Property(property="precio_b2c", type="number", format="float", example=3.50),
     *                     @OA\Property(property="categoria", type="string", example="Panadería")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error interno del servidor")
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/ApiProducto/create",
     *     summary="Registrar nuevo producto",
     *     description="Crea un nuevo recurso de producto en la base de datos.",
     *     tags={"Productos"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del producto",
     *         @OA\JsonContent(
     *             required={"nombre","precio_b2c","id_categoria"},
     *             @OA\Property(property="nombre", type="string", example="Torta de Chocolate"),
     *             @OA\Property(property="descripcion", type="string", example="Torta húmeda con fudge"),
     *             @OA\Property(property="precio_b2c", type="number", example=45.00),
     *             @OA\Property(property="id_categoria", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Producto creado"),
     *             @OA\Property(property="id", type="integer", example=15)
     *         )
     *     ),
     *     @OA\Response(response=400, description="Datos inválidos")
     * )
     */
    public function create() {}
}
?>