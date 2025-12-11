<?php

namespace Controllers\Mantenimientos;

use Controllers\PrivateController;
use Dao\Dao;
use Views\Renderer;
use Dao\Clientes\Clientes as ClienteDAO;

const CLIENTES_NEW = "mantenimiento_clientes_new";
const CLIENTES_UPD = "mantenimiento_clientes_update";
const CLIENTES_DEL = "mantenimiento_clientes_delete";

class Clientes extends PrivateController
{

    public function run(): void
    {
        $viewData = [];
        $tmpClientes = ClienteDAO::obtenerClientes();
        $viewData["clientes"] = [];
        $totalNota = 0;
        foreach ($tmpClientes as $cliente) {
            $clienteNormalizado = $cliente;
            $clienteNormalizado["nota"] = $cliente["evaluacion"] / 100 * 5;
            $totalNota += $clienteNormalizado["nota"];
            if ($clienteNormalizado["nota"] > 4.5) {
                $clienteNormalizado["grade"] = "A";
            } elseif ($clienteNormalizado["nota"] > 4.0) {
                $clienteNormalizado["grade"] = "B";
            } elseif ($clienteNormalizado["nota"] > 3.5) {
                $clienteNormalizado["grade"] = "C";
            } elseif ($clienteNormalizado["nota"] > 3.0) {
                $clienteNormalizado["grade"] = "D";
            } elseif ($clienteNormalizado["nota"] > 2.5) {
                $clienteNormalizado["grade"] = "E";
            } else {
                $clienteNormalizado["grade"] = "F";
            }
            $viewData["clientes"][] = $clienteNormalizado;
        }
        $viewData["total"] = count($viewData["clientes"]);
        $viewData["totalNota"] = $totalNota;

        $viewData[CLIENTES_NEW] = $this->isFeatureAutorized(CLIENTES_NEW);
        $viewData[CLIENTES_UPD] = $this->isFeatureAutorized(CLIENTES_UPD);
        $viewData[CLIENTES_DEL] = $this->isFeatureAutorized(CLIENTES_DEL);

        Renderer::render("mantenimientos/clientes/lista", $viewData);
    }
}
