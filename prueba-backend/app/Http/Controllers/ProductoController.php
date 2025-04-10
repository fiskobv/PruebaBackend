<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API de Gestión de Productos",
 *      description="API RESTful para gestionar productos, divisas y precios adicionales de productos.",
 *      @OA\Contact(
 *          email="tucorreo@example.com"
 *      )
 * )
 */

 class ProductoController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Obtener lista de productos",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos obtenida correctamente",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Camiseta básica"),
     *             @OA\Property(property="description", type="string", example="Camiseta de algodón 100%"),
     *             @OA\Property(property="price", type="number", format="float", example=25.5),
     *             @OA\Property(property="currency_id", type="integer", example=1),
     *             @OA\Property(property="tax_cost", type="number", format="float", example=2.55),
     *             @OA\Property(property="manufacturing_cost", type="number", format="float", example=10.0),
     *             @OA\Property(property="created_at", type="string", example="2025-04-09T21:14:01.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2025-04-09T21:14:01.000000Z"),
     *         ))
     *     )
     * )
     */
    public function index()
    {
        $productos = Producto::with('divisa')->get();
        return response()->json($productos);
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Crear un nuevo producto",
     *     tags={"Productos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description","price","currency_id","tax_cost","manufacturing_cost"},
     *             @OA\Property(property="name", type="string", example="Camiseta básica"),
     *             @OA\Property(property="description", type="string", example="Camiseta de algodón 100%"),
     *             @OA\Property(property="price", type="number", format="float", example=25.50),
     *             @OA\Property(property="currency_id", type="integer", example=1),
     *             @OA\Property(property="tax_cost", type="number", format="float", example=2.55),
     *             @OA\Property(property="manufacturing_cost", type="number", format="float", example=10.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado correctamente"
     *     ),
     *     @OA\Response(response=422, description="Datos inválidos proporcionados")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string',
            'description'       => 'required|string',
            'price'             => 'required|numeric',
            'currency_id'       => 'required|exists:divisas,id',
            'tax_cost'          => 'required|numeric',
            'manufacturing_cost'=> 'required|numeric'
        ]);

        $producto = Producto::create($data);

        return response()->json($producto, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Obtener un producto por ID",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         description="ID del producto",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example=1
     *     ),
     *     @OA\Response(response=200, description="Producto obtenido correctamente"),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
    public function show($id)
    {
        $producto = Producto::with('divisa')->findOrFail($id);
        return response()->json($producto);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Actualizar un producto existente",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         description="ID del producto a actualizar",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example=1
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nueva camiseta básica"),
     *             @OA\Property(property="description", type="string", example="Nueva descripción"),
     *             @OA\Property(property="price", type="number", format="float", example=30.00)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Producto actualizado correctamente"),
     *     @OA\Response(response=404, description="Producto no encontrado"),
     *     @OA\Response(response=422, description="Datos inválidos proporcionados")
     * )
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $data = $request->validate([
            'name'              => 'sometimes|required|string',
            'description'       => 'sometimes|required|string',
            'price'             => 'sometimes|required|numeric',
            'currency_id'       => 'sometimes|required|exists:divisas,id',
            'tax_cost'          => 'sometimes|required|numeric',
            'manufacturing_cost'=> 'sometimes|required|numeric'
        ]);

        $producto->update($data);
        return response()->json($producto);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Eliminar un producto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         description="ID del producto a eliminar",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example=1
     *     ),
     *     @OA\Response(response=204, description="Producto eliminado correctamente"),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return response()->json(null, 204);
    }
}
