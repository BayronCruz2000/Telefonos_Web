<?php
namespace Controllers\Checkout;

use Controllers\PublicController;
use Dao\Checkout\Pago as PagoDao;
use Views\Renderer;
use Utilities\Site;
use Utilities\Security;

class Pago extends PublicController
{
    public function run(): void
    {
        // 1. Manejo de Sesión
        if (session_status() == PHP_SESSION_NONE) { 
            session_start(); 
        }
        
        $usercod = session_id(); // ID de usuario temporal (anónimo)
        
        // 2. Verificar si el carrito tiene items
        $cartItems = \Dao\Cart\Cart::getCart($usercod);
        $total = 0;
        
        foreach($cartItems as $item) {
            $total += floatval($item["total"]);
        }
        
        // 3. Si no hay items, redirigir al carrito
        if (empty($cartItems) || $total <= 0) {
            Site::redirectTo("index.php?page=Checkout_Cart");
            return;
        }
        
        // 4. Procesar pago si se envió el formulario
        if ($this->isPostBack()) {
            $action = $_POST["action"] ?? "";
            
            if ($action === "process_payment") {
                // Validar que aún haya stock disponible
                $canProceed = true;
                $errors = [];
                
                foreach ($cartItems as $item) {
                    $productId = $item["productid"];
                    $quantity = $item["crrctd"];
                    
                    // Verificar stock usando la función del DAO del carrito
                    $availableStock = \Dao\Cart\Cart::getProductoDisponible($productId);
                    
                    if (empty($availableStock) || 
                        $availableStock[$productId]["productStock"] < $quantity) {
                        $canProceed = false;
                        $errors[] = "Producto {$item['productName']} sin stock suficiente";
                    }
                }
                
                if ($canProceed) {
                    // Registrar la transacción
                    $transactionId = PagoDao::createTransaction($usercod, $total);
                    
                    if ($transactionId) {
                        // Mover items del carrito temporal a órdenes completadas
                        PagoDao::completeOrder($usercod, $transactionId);
                        
                        // Limpiar carrito
                        \Dao\Cart\Cart::clearCart($usercod);
                        
                        // Redirigir a confirmación (podrías crear esta página después)
                        // Site::redirectTo("index.php?page=Checkout_Confirmation&tid=" . $transactionId);
                        
                        // Por ahora, mostramos mensaje de éxito
                        $viewData["success"] = true;
                        $viewData["transactionId"] = $transactionId;
                        $viewData["total"] = number_format($total, 2);
                    } else {
                        $viewData["error"] = "Error al procesar la transacción";
                    }
                } else {
                    $viewData["error"] = "Stock insuficiente: " . implode(", ", $errors);
                }
            }
        }
        
        // 5. Preparar datos para la vista
        $viewData = [];
        $viewData["items"] = $cartItems;
        $viewData["cartTotal"] = number_format($total, 2);
        $viewData["hasItems"] = $total > 0;
        $viewData["usercod"] = $usercod;
        
        // Agregar fecha y hora actual para mostrar
        $viewData["currentDate"] = date("d/m/Y");
        $viewData["currentTime"] = date("H:i");
        
        // Si no está definido success, es false
        if (!isset($viewData["success"])) {
            $viewData["success"] = false;
        }
        
        Renderer::render("checkout/pago", $viewData);
    }
}