<?php

namespace Controllers\Mantenimientos;

use Controllers\PrivateController;
use Utilities\Site;
use Utilities\Validators;
use Views\Renderer;
use Dao\Clientes\Clientes as DAOClientes;
use Exception;

const ClientesList = "index.php?page=Mantenimientos-Clientes";
const ClientView = "mantenimientos/clientes/form";

const CLIENTES_FORM_INS = "mantenimiento_clientes_new";
const CLIENTES_FORM_UPD = "mantenimiento_clientes_update";
const CLIENTES_FORM_DEL = "mantenimiento_clientes_delete";

class Cliente extends PrivateController
{
    private $modes = [
        "INS" => "Nuevo Cliente",
        "UPD" => "Editando %s",
        "DSP" => "Detalle de %s",
        "DEL" => "Eliminando %s"
    ];

    private string $mode = '';

    private string $codigo = '';
    private string $nombre = '';
    private string $direccion = '';
    private string $correo = '';
    private string $telefono = '';
    private string $estado = '';
    private int    $evaluacion = 0;

    private string $validationToken = '';

    private array $errores = [];
    /*
    X 1) Determinar como se llama este controlador (Modo). INS UPD DSP DEL
    X 2) Obtener el registro de la Modelo de Datos
    X 3) Si es un postback. Capturar los datos del formulario
    X 3.1 ) Validar los datos del formulario
    3.2 ) Aplicar el método segun el modo de la acción en la DB
    3.3 ) Enviar devuelta con mensaje a la lista
    X 4) Preparar la data para la vista
    X 5) Renderizar la vista
    */

    public function run(): void
    {
        try {
            $this->page_init();
            if ($this->isPostBack()) {
                $this->errores = $this->validarPostData();
                if (count($this->errores) === 0) {
                    try {
                        switch ($this->mode) {
                            case "INS":
                                //Llamar a Dao para Ingresar
                                $affectedRows = DAOClientes::crearCliente(
                                    $this->codigo,
                                    $this->nombre,
                                    $this->direccion,
                                    $this->correo,
                                    $this->telefono,
                                    $this->estado,
                                    $this->evaluacion
                                );
                                if ($affectedRows > 0) {
                                    Site::redirectToWithMsg(ClientesList, "Nuevo Cliente creado satisfactoriamente.");
                                }
                                break;
                            case "UPD":
                                //Llamar a Dao para Actualizar
                                $affectedRows = DAOClientes::actualizarCliente(
                                    $this->codigo,
                                    $this->nombre,
                                    $this->direccion,
                                    $this->correo,
                                    $this->telefono,
                                    $this->estado,
                                    $this->evaluacion
                                );
                                if ($affectedRows > 0) {
                                    Site::redirectToWithMsg(ClientesList, "Cliente actualizado satisfactoriamente.");
                                }
                                break;
                            case "DEL":
                                //Llamar a Dao para Eliminar
                                $affectedRows = DAOClientes::eliminarCliente(
                                    $this->codigo
                                );
                                if ($affectedRows > 0) {
                                    Site::redirectToWithMsg(ClientesList, "Cliente eliminado satisfactoriamente.");
                                }
                                break;
                        }
                    } catch (Exception $err) {
                        error_log($err, 0);
                        $this->errores[] = $err;
                    }
                }
            }
            Renderer::render(
                ClientView,
                $this->preparar_datos_vista()
            );
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            Site::redirectToWithMsg(ClientesList, "Sucedió un problema. Reintente nuevamente.");
        }
    }
    private function page_init()
    {
        if (isset($_GET["mode"]) && isset($this->modes[$_GET["mode"]])) {
            $this->mode = $_GET["mode"];
            if ($this->mode !== "INS") {
                $tmpCodigo = '';
                if (isset($_GET["codigo"])) {
                    $tmpCodigo = $_GET["codigo"];
                } else {
                    throw new Exception("Código no es Válido");
                }
                // Extraer datos de la DB
                $tmpCliente = DAOClientes::obtenerClientePorCodigo($tmpCodigo);
                if (count($tmpCliente) === 0) {
                    throw new Exception("No se encontró Registro");
                }
                $this->codigo = $tmpCliente["codigo"];
                $this->nombre = $tmpCliente["nombre"];
                $this->direccion = $tmpCliente["direccion"];
                $this->telefono = $tmpCliente["telefono"];
                $this->correo = $tmpCliente["correo"];
                $this->estado = $tmpCliente["estado"];
                $this->evaluacion = $tmpCliente["evaluacion"];
            }
        } else {
            throw new Exception("Valor de Mode no es Válido");
        }
        if ($_GET["mode"] === "INS") {
            if (!$this->isFeatureAutorized(CLIENTES_FORM_INS)) {
                throw new Exception("No Tiene Autorizado crear nuevo Cliente");
            }
        }
        if ($_GET["mode"] === "UPD") {
            if (!$this->isFeatureAutorized(CLIENTES_FORM_UPD)) {
                throw new Exception("No Tiene Autorizado editar Cliente");
            }
        }
        if ($_GET["mode"] === "DEL") {
            if (!$this->isFeatureAutorized(CLIENTES_FORM_DEL)) {
                throw new Exception("No Tiene Autorizado eliminar Cliente");
            }
        }
    }

    private function validarPostData(): array
    {
        $errors = [];

        $this->validationToken = $_POST["vlt"] ?? '';
        if (isset($_SESSION[$this->name . "_token"]) &&  $_SESSION[$this->name . "_token"] !== $this->validationToken) {
            throw new Exception("Error de validación de Token");
        }

        $this->codigo = $_POST["codigo"] ?? '';
        $this->nombre = $_POST["nombre"] ?? '';
        $this->direccion = $_POST["direccion"] ?? '';
        $this->correo = $_POST["correo"] ?? '';
        $this->telefono = $_POST["telefono"] ?? '';
        $this->estado = $_POST["estado"] ?? 'ACT';
        $this->evaluacion = intval($_POST["evaluacion"] ?? '');

        // Validaciones
        if (Validators::IsEmpty($this->nombre)) {
            $errors[] = "Nombre no puede ir vacio.";
        }

        if (!in_array($this->estado, ["ACT", "INA"])) {
            $errors[] = "Estado Incorrecto.";
        }

        return $errors;
    }

    private function generarTokenDeValidacion()
    {
        $this->validationToken = md5(gettimeofday(true) . $this->name . rand(1000, 9999));
        $_SESSION[$this->name . "_token"] = $this->validationToken;
    }

    private function preparar_datos_vista()
    {
        $viewData = [];
        $viewData["mode"] = $this->mode;

        $viewData["modeDsc"] = $this->modes[$this->mode];

        if ($this->mode !== "INS") {
            $viewData["modeDsc"] = sprintf($viewData["modeDsc"], $this->nombre);
        }

        $viewData["codigo"] = $this->codigo;
        $viewData["nombre"] = $this->nombre;
        $viewData["direccion"] = $this->direccion;
        $viewData["telefono"] = $this->telefono;
        $viewData["correo"] = $this->correo;
        $viewData["estado"] = $this->estado;
        $viewData["evaluacion"] = $this->evaluacion;

        $this->generarTokenDeValidacion();
        $viewData["token"] = $this->validationToken;

        $viewData["errores"] = $this->errores;
        $viewData["hasErrores"] = count($this->errores) > 0;

        $viewData["codigoReadonly"] = $this->mode !== "INS" ? "readonly" : "";

        $viewData["readonly"] = in_array($this->mode, ["DSP", "DEL"]) ? "readonly" : "";

        $viewData["isDisplay"] = $this->mode === "DSP";

        $viewData["selected" . $this->estado] = "selected";
        return $viewData;
    }
}
