<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\PrecioProducto;

class PrecioProductoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products/{id}/prices",
     *     summary="Obtener lista de precios adicionales de un producto",
     *     tags={"Precios Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de precios obtenida correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="product_id", type="integer", example=1),
     *                 @OA\Property(property="currency_id", type="integer", example=2),
     *                 @OA\Property(property="price", type="number", format="float", example=22000),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-09T21:14:01.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-09T21:14:01.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
    public function index($id)
    {
        $producto = Producto::findOrFail($id);
        $precios = $producto->precios()->with('divisa')->get();
        return response()->json($precios);
    }

    /**
     * @OA\Post(
     *     path="/api/products/{id}/prices",
     *     summary="Crear un nuevo precio adicional para un producto",
     *     tags={"Precios Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"currency_id", "price"},
     *             @OA\Property(property="currency_id", type="integer", example=2),
     *             @OA\Property(property="price", type="number", format="float", example=22000)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Precio adicional creado correctamente"),
     *     @OA\Response(response=404, description="Producto no encontrado"),
     *     @OA\Response(response=422, description="Datos inválidos proporcionados")
     * )
     */
    public function store(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $data = $request->validate([
            'currency_id' => 'required|exists:divisas,id',
            'price'       => 'required|numeric'
        ]);

        $data['product_id'] = $producto->id;
        $precio = PrecioProducto::create($data);
        return response()->json($precio, 201);
    }
}
