<?php
namespace Controllers\Checkout;

use Controllers\PublicController;
use Dao\Cart\Cart as CartDao;
use Views\Renderer;
use Utilities\Site;

class Cart extends PublicController
{
    public function run(): void
    {
    
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $usercod = session_id();

       
        if ($this->isPostBack()) {
            $action = $_POST["action"] ?? "";
            $productId = intval($_POST["productId"] ?? 0);
            
            if ($action === "add") {
                $price = floatval($_POST["price"] ?? 0);
                $qty = 1; 
                
               
                $result = CartDao::addItem($usercod, $productId, $qty, $price);
                
                if ($result === false) {
                    
                }
                
                Site::redirectTo("index.php?page=Checkout_Cart");
            }
            
            if ($action === "delete") {
                CartDao::deleteItem($usercod, $productId);
                Site::redirectTo("index.php?page=Checkout_Cart");
            }
        }

       
        $viewData = [];
        $viewData["items"] = CartDao::getCart($usercod);
        
        $total = 0;
        foreach($viewData["items"] as $item) {
            $total += floatval($item["total"]);
        }
        $viewData["cartTotal"] = number_format($total, 2);
        $viewData["hasItems"] = $total > 0;

        Renderer::render("checkout/cart", $viewData);
    }
}
?>