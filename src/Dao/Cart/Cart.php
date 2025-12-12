<?php
namespace Dao\Cart;

use Dao\Table;

class Cart extends Table
{
    public static function getCart($usercod)
    {
        $sqlstr = "SELECT c.productid, p.productName, p.productImgUrl, c.crrctd, c.crrprc, (c.crrctd * c.crrprc) as total 
                   FROM carretilla_anon c 
                   INNER JOIN products p ON c.productid = p.productId 
                   WHERE c.usercod = :usercod";
        return self::obtenerRegistros($sqlstr, ["usercod" => $usercod]);
    }

    public static function addItem($usercod, $productId, $quantity, $price)
    {
        $productoInfo = self::getProductoDisponible($productId);
        if (empty($productoInfo) || $productoInfo[$productId]["productStock"] < $quantity) {
             return false; 
        }

        $sqlCheck = "SELECT crrctd FROM carretilla_anon WHERE usercod = :usercod AND productid = :productid";
        $exist = self::obtenerUnRegistro($sqlCheck, ["usercod" => $usercod, "productid" => $productId]);

        $sqlstr = "";
        $params = []; 

        if ($exist) {
            
            $sqlstr = "UPDATE carretilla_anon SET crrctd = crrctd + :quantity, crrfching = NOW() WHERE usercod = :usercod AND productid = :productid";
            $params = [
                "usercod"   => $usercod,
                "productid" => $productId,
                "quantity"  => $quantity
            ];
        } else {
            
            $sqlstr = "INSERT INTO carretilla_anon (usercod, productid, crrctd, crrprc, crrfching) 
                       VALUES (:usercod, :productid, :quantity, :price, NOW())";
            $params = [
                "usercod"   => $usercod,
                "productid" => $productId,
                "quantity"  => $quantity,
                "price"     => $price
            ];
        }

        return self::executeNonQuery($sqlstr, $params);
    }

  
    public static function deleteItem($usercod, $productId)
    {
        $sqlstr = "DELETE FROM carretilla_anon WHERE usercod = :usercod AND productid = :productid";
        return self::executeNonQuery($sqlstr, ["usercod" => $usercod, "productid" => $productId]);
    }

   
    public static function getProductosDisponibles()
    {
        $sqlAllProductosActivos = "SELECT * from products where productStatus in ('ACT');";
        $productosDisponibles = self::obtenerRegistros($sqlAllProductosActivos, array());

        
        $deltaAutorizada = \Utilities\Cart\CartFns::getAuthTimeDelta();
       

        
        $deltaNAutorizada = \Utilities\Cart\CartFns::getUnAuthTimeDelta();
        
      
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