<?php
namespace Dao\Cart;

use Dao\Table;

class Cart extends Table
{
    /* =========================================================
       PARTE 1: OPERATIVA (Lo que yo te di)
       Permite Agregar, Quitar y Ver el carrito
       ========================================================= */

    // Obtener los productos del carrito del usuario
    public static function getCart($usercod)
    {
        // Asegúrate que la tabla se llame 'products' o cambia a 'telefonos'
        $sqlstr = "SELECT c.productid, p.productName, p.productImgUrl, c.crrctd, c.crrprc, (c.crrctd * c.crrprc) as total 
                   FROM carretilla_anon c 
                   INNER JOIN products p ON c.productid = p.productId 
                   WHERE c.usercod = :usercod";
        return self::obtenerRegistros($sqlstr, ["usercod" => $usercod]);
    }

    // Agregar item (Con validación de stock usando TU lógica)
    // Agregar item (Con validación de stock usando TU lógica)
    public static function addItem($usercod, $productId, $quantity, $price)
    {
        // 1. Validar Stock
        $productoInfo = self::getProductoDisponible($productId);
        if (empty($productoInfo) || $productoInfo[$productId]["productStock"] < $quantity) {
             return false; 
        }

        // 2. Verificar si existe
        $sqlCheck = "SELECT crrctd FROM carretilla_anon WHERE usercod = :usercod AND productid = :productid";
        $exist = self::obtenerUnRegistro($sqlCheck, ["usercod" => $usercod, "productid" => $productId]);

        $sqlstr = "";
        $params = []; // Creamos un array vacío para llenarlo según el caso

        if ($exist) {
            // CASO ACTUALIZAR: Solo necesitamos cantidad, usuario y producto. NO PRECIO.
            $sqlstr = "UPDATE carretilla_anon SET crrctd = crrctd + :quantity, crrfching = NOW() WHERE usercod = :usercod AND productid = :productid";
            $params = [
                "usercod"   => $usercod,
                "productid" => $productId,
                "quantity"  => $quantity
            ];
        } else {
            // CASO INSERTAR: Aquí sí necesitamos el precio.
            $sqlstr = "INSERT INTO carretilla_anon (usercod, productid, crrctd, crrprc, crrfching) 
                       VALUES (:usercod, :productid, :quantity, :price, NOW())";
            $params = [
                "usercod"   => $usercod,
                "productid" => $productId,
                "quantity"  => $quantity,
                "price"     => $price
            ];
        }

        // Ejecutamos con los parámetros correctos para cada caso
        return self::executeNonQuery($sqlstr, $params);
    }

    // Eliminar item
    public static function deleteItem($usercod, $productId)
    {
        $sqlstr = "DELETE FROM carretilla_anon WHERE usercod = :usercod AND productid = :productid";
        return self::executeNonQuery($sqlstr, ["usercod" => $usercod, "productid" => $productId]);
    }

    /* =========================================================
       PARTE 2: INVENTARIO (Lo que tú tenías)
       Calcula el stock real basándose en reservas de tiempo
       ========================================================= */

    public static function getProductosDisponibles()
    {
        $sqlAllProductosActivos = "SELECT * from products where productStatus in ('ACT');";
        $productosDisponibles = self::obtenerRegistros($sqlAllProductosActivos, array());

        // Sacar el stock de productos con carretilla autorizada (Usuarios logueados)
        $deltaAutorizada = \Utilities\Cart\CartFns::getAuthTimeDelta();
        // Nota: Asegúrate que exista la tabla 'carretilla' para usuarios logueados
        // Si no la usas aún, esta parte podría dar error si la tabla no existe.
        /* $sqlCarretillaAutorizada = "select productId, sum(crrctd) as reserved
            from carretilla where TIME_TO_SEC(TIMEDIFF(now(), crrfching)) <= :delta
            group by productId;";
        $prodsCarretillaAutorizada = self::obtenerRegistros(
            $sqlCarretillaAutorizada,
            array("delta" => $deltaAutorizada)
        );
        */

        // Sacar el stock de productos con carretilla NO autorizada (Anónimos)
        $deltaNAutorizada = \Utilities\Cart\CartFns::getUnAuthTimeDelta();
        
        // CORRECCIÓN: Usamos 'carretilla_anon' en lugar de 'carretillaanom'
        $sqlCarretillaNAutorizada = "select productId, sum(crrctd) as reserved
            from carretilla_anon where TIME_TO_SEC(TIMEDIFF(now(), crrfching)) <= :delta
            group by productId;";
            
        $prodsCarretillaNAutorizada = self::obtenerRegistros(
            $sqlCarretillaNAutorizada,
            array("delta" => $deltaNAutorizada)
        );

        $productosCurados = array();
        foreach ($productosDisponibles as $producto) {
            if (!isset($productosCurados[$producto["productId"]])) {
                $productosCurados[$producto["productId"]] = $producto;
                // Parche por si la tabla no tiene columna stock
                if(!isset($productosCurados[$producto["productId"]]["productStock"])) {
                    $productosCurados[$producto["productId"]]["productStock"] = 999;
                }
            }
        }

        // Restar Reservas (Descomentar si usas la tabla carretilla de logueados)
        /*
        foreach ($prodsCarretillaAutorizada as $producto) {
            if (isset($productosCurados[$producto["productId"]])) {
                $productosCurados[$producto["productId"]]["productStock"] -= $producto["reserved"];
            }
        }
        */

        foreach ($prodsCarretillaNAutorizada as $producto) {
            if (isset($productosCurados[$producto["productId"]])) {
                $productosCurados[$producto["productId"]]["productStock"] -= $producto["reserved"];
            }
        }

        return $productosCurados;
    }

    public static function getProductoDisponible($productId)
    {
        $sqlAllProductosActivos = "SELECT * from products where productStatus in ('ACT') and productId=:productId;";
        $productosDisponibles = self::obtenerRegistros($sqlAllProductosActivos, array("productId" => $productId));
        
        if(empty($productosDisponibles)) return [];

        // Calcular reservas anónimas
        $deltaNAutorizada = \Utilities\Cart\CartFns::getUnAuthTimeDelta();
        $sqlCarretillaNAutorizada = "select productId, sum(crrctd) as reserved
            from carretilla_anon where productId = :productId and TIME_TO_SEC(TIMEDIFF(now(), crrfching)) <= :delta
            group by productId;";
            
        $prodsCarretillaNAutorizada = self::obtenerRegistros(
            $sqlCarretillaNAutorizada,
            array("productId" => $productId, "delta" => $deltaNAutorizada)
        );

        $productosCurados = array();
        foreach ($productosDisponibles as $producto) {
             $productosCurados[$producto["productId"]] = $producto;
             if(!isset($productosCurados[$producto["productId"]]["productStock"])) {
                 $productosCurados[$producto["productId"]]["productStock"] = 999;
             }
        }

        foreach ($prodsCarretillaNAutorizada as $producto) {
            if (isset($productosCurados[$producto["productId"]])) {
                $productosCurados[$producto["productId"]]["productStock"] -= $producto["reserved"];
            }
        }
        
        return $productosCurados;
    }
}
?>