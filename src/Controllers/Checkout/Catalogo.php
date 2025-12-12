<?php
/**
 * @category Controller
 * @package  Controllers\Checkout
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  Comercial http://
 * @version  CVS:1.0.0
 * @link     http://url.com
 */
 namespace Controllers\Checkout;


use Controllers\PrivateController;

/**
 * @category Public
 * @package  Controllers\Checkout;
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  MIT http://
 * @link     http://
 */
class Catalogo extends PrivateController
{
    /**
     * @return void
     */
    public function run():void
    {
        
        $producto = \Dao\Productos::getAll();
        $carretilla = \Dao\Carretilla::getAll(\Utilities\Security::getUserId());

        $carrAssoc = array();
        foreach($carretilla as $carr) {
            $carrAssoc[$carr["prdcod"]] = $carr;
        }

        foreach($producto as $prod) {
            if (isset($carrAssoc[$prod["prdcod"]])) {
                $prod["enCarretilla"] = true;
            } else {
                $prod["enCarretilla"] = false;
            }
        }
        \Views\Renderer::render("abc", array("productos" => $producto));
    }
}

?>
