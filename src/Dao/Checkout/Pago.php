<?php
namespace Dao\Checkout;

use Dao\Table;

class Pago extends Table
{
    /**
     * @param string 
     * @param float 
     * @return int|false 
     */
    public static function createTransaction($usercod, $total)
    {
       
        $transactionId = "TXN-" . date("YmdHis") . "-" . substr(md5($usercod . time()), 0, 8);
        $paymentMethod = "PAYPAL"; 
        
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
     * @param string 
     * @param string 
     * @return bool
     */
    public static function completeOrder($usercod, $transactionId)
    {
        try {
           
            $sqlstr = "SELECT * FROM carretilla_anon WHERE usercod = :usercod";
            $cartItems = self::obtenerRegistros($sqlstr, ["usercod" => $usercod]);
            
            if (empty($cartItems)) {
                return false;
            }
            
         
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

            }
            
           
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
     * @param string 
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
     * @param string 
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
     * @param string 
     * @return array|false
     */
    public static function verifyTransaction($transactionId)
    {
        $sqlstr = "SELECT * FROM transacciones_pago WHERE transaction_id = :transaction_id";
        return self::obtenerUnRegistro($sqlstr, ["transaction_id" => $transactionId]);
    }
    
    /**
     * @param int 
     * @param int 
     * @return bool
     */
    private static function updateProductStock($productId, $quantity)
    {
 
        $sqlstr = "UPDATE products SET productStock = productStock - :quantity 
                  WHERE productId = :productId AND productStock >= :quantity";
        
        return self::executeNonQuery($sqlstr, [
            "productId" => $productId,
            "quantity" => $quantity
        ]);
    }
}