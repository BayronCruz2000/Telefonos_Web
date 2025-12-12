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
       
        if (session_status() == PHP_SESSION_NONE) { 
            session_start(); 
        }
        
        $usercod = session_id(); 
        
       
        $cartItems = \Dao\Cart\Cart::getCart($usercod);
        $total = 0;
        
        foreach($cartItems as $item) {
            $total += floatval($item["total"]);
        }
        
        
        if (empty($cartItems) || $total <= 0) {
            Site::redirectTo("index.php?page=Checkout_Cart");
            return;
        }
        
        
        if ($this->isPostBack()) {
            $action = $_POST["action"] ?? "";
            
            if ($action === "process_payment") {
               
                $canProceed = true;
                $errors = [];
                
                foreach ($cartItems as $item) {
                    $productId = $item["productid"];
                    $quantity = $item["crrctd"];
                    
                 
                    $availableStock = \Dao\Cart\Cart::getProductoDisponible($productId);
                    
                    if (empty($availableStock) || 
                        $availableStock[$productId]["productStock"] < $quantity) {
                        $canProceed = false;
                        $errors[] = "Producto {$item['productName']} sin stock suficiente";
                    }
                }
                
                if ($canProceed) {
                    
                    $transactionId = PagoDao::createTransaction($usercod, $total);
                    
                    if ($transactionId) {
                        PagoDao::completeOrder($usercod, $transactionId);
                        
                       
                        \Dao\Cart\Cart::clearCart($usercod);
                        
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
        
       
        $viewData = [];
        $viewData["items"] = $cartItems;
        $viewData["cartTotal"] = number_format($total, 2);
        $viewData["hasItems"] = $total > 0;
        $viewData["usercod"] = $usercod;
        
    
        $viewData["currentDate"] = date("d/m/Y");
        $viewData["currentTime"] = date("H:i");
        

        if (!isset($viewData["success"])) {
            $viewData["success"] = false;
        }
        
        Renderer::render("checkout/pago", $viewData);
    }
}