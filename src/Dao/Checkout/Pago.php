<?php
namespace Dao\Checkout;

use Dao\Table;

class Pago extends Table
{
    /**
     * Crear una nueva transacción de pago
     * @param string $usercod ID del usuario
     * @param float $total Monto total
     * @return int|false ID de la transacción o false en error
     */
    public static function createTransaction($usercod, $total)
    {
        // Generar ID de transacción único
        $transactionId = "TXN-" . date("YmdHis") . "-" . substr(md5($usercod . time()), 0, 8);
        $paymentMethod = "PAYPAL"; // Por defecto PayPal, puedes hacerlo dinámico
        
        try {
            $sqlstr = "INSERT INTO transacciones_pago 
                      (transaction_id, usercod, total, metodo_pago, estado, fecha_creacion) 
                      VALUES (:transaction_id, :usercod, :total, :metodo_pago, 'PENDIENTE', NOW())";
            
            $params = [
                "transaction_id" => $transactionId,
                "usercod" => $usercod,
                "total" => $total,
                "metodo_pago" => $paymentMethod
            ];
            
            if (self::executeNonQuery($sqlstr, $params)) {
                return $transactionId;
            }
        } catch (\Exception $ex) {
            error_log("Error al crear transacción: " . $ex->getMessage());
        }
        
        return false;
    }
    
    /**
     * Completar la orden moviendo items del carrito a órdenes
     * @param string $usercod ID del usuario
     * @param string $transactionId ID de la transacción
     * @return bool
     */
    public static function completeOrder($usercod, $transactionId)
    {
        try {
            // 1. Obtener items del carrito
            $sqlstr = "SELECT * FROM carretilla_anon WHERE usercod = :usercod";
            $cartItems = self::obtenerRegistros($sqlstr, ["usercod" => $usercod]);
            
            if (empty($cartItems)) {
                return false;
            }
            
            // 2. Insertar en tabla de órdenes detalle
            foreach ($cartItems as $item) {
                $sqlDetail = "INSERT INTO ordenes_detalle 
                             (transaction_id, productid, cantidad, precio_unitario, total) 
                             VALUES (:transaction_id, :productid, :cantidad, :precio_unitario, :total)";
                
                $paramsDetail = [
                    "transaction_id" => $transactionId,
                    "productid" => $item["productid"],
                    "cantidad" => $item["crrctd"],
                    "precio_unitario" => $item["crrprc"],
                    "total" => $item["crrctd"] * $item["crrprc"]
                ];
                
                self::executeNonQuery($sqlDetail, $paramsDetail);
                
                // 3. Actualizar stock del producto (opcional, dependiendo de tu lógica)
                // self::updateProductStock($item["productid"], $item["crrctd"]);
            }
            
            // 4. Actualizar estado de la transacción a COMPLETADA
            $sqlUpdate = "UPDATE transacciones_pago SET estado = 'COMPLETADA', fecha_completado = NOW() 
                         WHERE transaction_id = :transaction_id";
            
            self::executeNonQuery($sqlUpdate, ["transaction_id" => $transactionId]);
            
            return true;
            
        } catch (\Exception $ex) {
            error_log("Error al completar orden: " . $ex->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener historial de transacciones del usuario
     * @param string $usercod ID del usuario
     * @return array
     */
    public static function getTransactionHistory($usercod)
    {
        $sqlstr = "SELECT * FROM transacciones_pago 
                  WHERE usercod = :usercod 
                  ORDER BY fecha_creacion DESC 
                  LIMIT 10";
        
        return self::obtenerRegistros($sqlstr, ["usercod" => $usercod]);
    }
    
    /**
     * Obtener detalles de una transacción específica
     * @param string $transactionId ID de la transacción
     * @return array
     */
    public static function getTransactionDetails($transactionId)
    {
        $sqlstr = "SELECT od.*, p.productName, p.productImgUrl 
                  FROM ordenes_detalle od
                  INNER JOIN products p ON od.productid = p.productId
                  WHERE od.transaction_id = :transaction_id";
        
        return self::obtenerRegistros($sqlstr, ["transaction_id" => $transactionId]);
    }
    
    /**
     * Verificar si una transacción existe y su estado
     * @param string $transactionId ID de la transacción
     * @return array|false
     */
    public static function verifyTransaction($transactionId)
    {
        $sqlstr = "SELECT * FROM transacciones_pago WHERE transaction_id = :transaction_id";
        return self::obtenerUnRegistro($sqlstr, ["transaction_id" => $transactionId]);
    }
    
    /**
     * Actualizar stock del producto después de una compra
     * @param int $productId ID del producto
     * @param int $quantity Cantidad vendida
     * @return bool
     */
    private static function updateProductStock($productId, $quantity)
    {
        // Solo si tu tabla products tiene la columna productStock
        $sqlstr = "UPDATE products SET productStock = productStock - :quantity 
                  WHERE productId = :productId AND productStock >= :quantity";
        
        return self::executeNonQuery($sqlstr, [
            "productId" => $productId,
            "quantity" => $quantity
        ]);
    }
}