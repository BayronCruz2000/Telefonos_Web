<?php

namespace Controllers\Transacciones;

use Controllers\PublicController;
use Dao\Transacciones\Transacciones;
use Views\Renderer;

class Historico extends PublicController
{
    public function run(): void
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {

            $usuarios = Transacciones::obtenerUsuarios();

            $usuario_id = null;
            if (isset($_GET['user']) && !empty($_GET['user'])) {
                $usuario_id = $_GET['user'];
            } elseif (!empty($usuarios)) {
                $usuario_id = $usuarios[0]['usuario_id'];
            }

            if (empty($usuarios) || !$usuario_id) {
                Renderer::render("transacciones/historico", [
                    "usuarios" => $usuarios,
                    "usuario_id" => $usuario_id,
                    "tiene_transacciones" => false,
                    "transacciones" => [],
                    "total_transacciones" => 0,
                    "total_gastado" => "0.00",
                    "completadas" => 0,
                    "pendientes" => 0,
                    "canceladas" => 0,
                    "pagina_actual" => 1,
                    "total_paginas" => 0,
                    "mostrar_paginacion" => false
                ]);
                return;
            }

            $limit = 10;
            $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
            $offset = ($pagina - 1) * $limit;

            $transacciones = Transacciones::obtenerPorUsuario($usuario_id, $limit, $offset);
            $totalTransacciones = Transacciones::contarPorUsuario($usuario_id);
            $totalPaginas = ($totalTransacciones > 0) ? ceil($totalTransacciones / $limit) : 0;
            $estadisticas = Transacciones::obtenerEstadisticas($usuario_id);

            $transaccionesProcesadas = [];
            foreach ($transacciones as $trans) {
                $productos = json_decode($trans['productos_json'], true);
                if (!is_array($productos)) {
                    $productos = [];
                }

                $transaccionesProcesadas[] = [
                    'id' => $trans['transaccion_id'],
                    'order_id' => $trans['order_id'],
                    'fecha' => date('d/m/Y', strtotime($trans['fecha'])),
                    'fecha_completa' => date('d/m/Y H:i:s', strtotime($trans['fecha'])),
                    'total' => number_format($trans['total'], 2),
                    'estado' => $trans['estado'],
                    'estado_texto' => $this->traducirEstado($trans['estado']),
                    'estado_clase' => $this->obtenerClaseEstado($trans['estado']),
                    'metodo_pago' => $trans['metodo_pago'] ?? 'PayPal',
                    'detalles' => $trans['detalles'] ?? '',
                    'productos' => $productos,
                    'cantidad_productos' => count($productos)
                ];
            }

            Renderer::render("transacciones/historico", [
                'usuarios' => $usuarios,
                'usuario_id' => $usuario_id,
                'transacciones' => $transaccionesProcesadas,
                'total_transacciones' => $totalTransacciones,
                'total_gastado' => number_format($estadisticas['total_gastado'] ?? 0, 2),
                'completadas' => $estadisticas['completadas'] ?? 0,
                'pendientes' => $estadisticas['pendientes'] ?? 0,
                'canceladas' => $estadisticas['canceladas'] ?? 0,
                'pagina_actual' => $pagina,
                'total_paginas' => $totalPaginas,
                'tiene_transacciones' => $totalTransacciones > 0,
                'mostrar_paginacion' => $totalPaginas > 1
            ]);
        } catch (\Exception $e) {
            error_log("Error en Historico::run(): " . $e->getMessage());

            Renderer::render("transacciones/historico", [
                "usuarios" => [],
                "usuario_id" => null,
                "tiene_transacciones" => false,
                "transacciones" => [],
                "total_transacciones" => 0,
                "total_gastado" => "0.00",
                "completadas" => 0,
                "pendientes" => 0,
                "canceladas" => 0,
                "pagina_actual" => 1,
                "total_paginas" => 0,
                "mostrar_paginacion" => false,
                "error_message" => "Error al cargar las transacciones: " . $e->getMessage()
            ]);
        }
    }

    private function traducirEstado($estado)
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada'
        ];
        return $estados[$estado] ?? ucfirst($estado);
    }

    private function obtenerClaseEstado($estado)
    {
        $clases = [
            'pendiente' => 'warning',
            'completada' => 'success',
            'cancelada' => 'danger'
        ];
        return $clases[$estado] ?? 'secondary';
    }
}
