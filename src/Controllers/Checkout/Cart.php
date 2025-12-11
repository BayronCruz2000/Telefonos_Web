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
        // 1. Manejo de Sesión (Usuario Anónimo)
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $usercod = session_id(); // Usamos la sesión como ID temporal

        // 2. Procesar Acciones (POST)
        if ($this->isPostBack()) {
            $action = $_POST["action"] ?? "";
            $productId = intval($_POST["productId"] ?? 0);
            
            if ($action === "add") {
                $price = floatval($_POST["price"] ?? 0);
                $qty = 1; // Por defecto agregamos 1
                
                // Intentamos agregar (Tu DAO validará el stock automáticamente)
                $result = CartDao::addItem($usercod, $productId, $qty, $price);
                
                if ($result === false) {
                    // Si falla por stock, podrías guardar un error en sesión para mostrarlo
                    // Por ahora, simplemente recargamos
                }
                
                Site::redirectTo("index.php?page=Checkout_Cart");
            }
            
            if ($action === "delete") {
                CartDao::deleteItem($usercod, $productId);
                Site::redirectTo("index.php?page=Checkout_Cart");
            }
        }

        // 3. Preparar Datos para la Vista (GET)
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