<?php

namespace Controllers\Catalogo;

use Controllers\PublicController;
use Dao\Catalogo\Telefonos;

class Catalogo extends PublicController
{
    public function run(): void
    {
        $dao = new Telefonos();
        $telefonos = $dao->obtenerTodos();

        
        if (empty($telefonos)) {
            $telefonos = [
                ["id" => 1, "nombre" => "iPhone 15", "descripcion" => "Último modelo Apple", "precio" => "999.00", "imagen" => "https://via.placeholder.com/150", "disponibilidad" => 10],
                ["id" => 2, "nombre" => "Samsung Galaxy S23", "descripcion" => "Alta gama Samsung", "precio" => "899.00", "imagen" => "https://via.placeholder.com/150", "disponibilidad" => 15],
                ["id" => 3, "nombre" => "Xiaomi Mi 13", "descripcion" => "Potente y económico", "precio" => "699.00", "imagen" => "https://via.placeholder.com/150", "disponibilidad" => 20],
                ["id" => 4, "nombre" => "Google Pixel 8", "descripcion" => "Experiencia Android pura", "precio" => "799.00", "imagen" => "https://via.placeholder.com/150", "disponibilidad" => 8],
                ["id" => 5, "nombre" => "OnePlus 12", "descripcion" => "Rendimiento extremo", "precio" => "749.00", "imagen" => "https://via.placeholder.com/150", "disponibilidad" => 12],
                ["id" => 6, "nombre" => "Motorola Edge 40", "descripcion" => "Pantalla curva", "precio" => "699.00", "imagen" => "https://via.placeholder.com/150", "disponibilidad" => 9],
                ["id" => 7, "nombre" => "Sony Xperia 1 V", "descripcion" => "Cámara profesional", "precio" => "949.00", "imagen" => "https://via.placeholder.com/150", "disponibilidad" => 5],
                ["id" => 8, "nombre" => "Huawei P60 Pro", "descripcion" => "Gran batería", "precio" => "799.00", "imagen" => "https://via.placeholder.com/150", "disponibilidad" => 11],
            ];
        }

        $viewData = ["telefonos" => $telefonos];

        \Views\Renderer::render("catalogo/catalogo", $viewData);
    }
}
