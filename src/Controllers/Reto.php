<?php

namespace Controllers;

use Views\Renderer;

class Reto extends PublicController
{
    public function run(): void
    {
        $arrDatos = [
            "nombre" => "Fulanito de Tal",
            "cuenta" => "0801919239112",
            "correo" => "unCorreo@corres.com",
            "colores" => [
                "azul",
                "verde",
                "café",
                "gris",
                "blanco"
            ]
        ];
        Renderer::render("reto", $arrDatos);
    }
}
