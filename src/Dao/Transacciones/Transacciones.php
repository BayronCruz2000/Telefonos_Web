<?php

namespace Dao\Transacciones;

use Dao\Table;

class Transacciones extends Table
{
    public static function crearTransaccion($data)
    {
        $sqlstr = "INSERT INTO transacciones 
                  (usuario_id, order_id, total, estado, metodo_pago, detalles, productos_json) 
                  VALUES 
                  (:usuario_id, :order_id, :total, :estado, :metodo_pago, :detalles, :productos_json)";

        return self::executeNonQuery($sqlstr, $data);
    }

    public static function obtenerPorUsuario($usuario_id, $limit = 20, $offset = 0)
    {
        $limit = intval($limit);
        $offset = intval($offset);

        $sqlstr = "SELECT * FROM transacciones 
                  WHERE usuario_id = :usuario_id 
                  ORDER BY fecha DESC 
                  LIMIT $limit OFFSET $offset";

        return self::obtenerRegistros($sqlstr, ["usuario_id" => $usuario_id]);
    }

    public static function contarPorUsuario($usuario_id)
    {
        $sqlstr = "SELECT COUNT(*) as total FROM transacciones WHERE usuario_id = :usuario_id";
        $result = self::obtenerUnRegistro($sqlstr, ["usuario_id" => $usuario_id]);
        return $result['total'] ?? 0;
    }

    public static function obtenerUsuarios()
    {
        try {
            $sqlstr = "SELECT 
                       usuario_id,
                       usuario_id as nombre_usuario,
                       COUNT(*) as total_compras,
                       MAX(fecha) as ultima_compra
                       FROM transacciones 
                       GROUP BY usuario_id
                       ORDER BY usuario_id ASC";

            $resultados = self::obtenerRegistros($sqlstr, []);

            if (empty($resultados)) {
                $sqlstr = "SELECT DISTINCT usuario_id FROM transacciones ORDER BY usuario_id ASC";
                $resultados = self::obtenerRegistros($sqlstr, []);

                $usuarios = [];
                foreach ($resultados as $row) {
                    $usuarios[] = [
                        'usuario_id' => $row['usuario_id'],
                        'nombre_usuario' => $row['usuario_id'],
                        'total_compras' => 1,
                        'ultima_compra' => date('Y-m-d H:i:s')
                    ];
                }
                return $usuarios;
            }

            return $resultados;
        } catch (\Exception $e) {
            error_log("Error en obtenerUsuarios: " . $e->getMessage());

            try {
                $sqlstr = "SELECT DISTINCT usuario_id FROM transacciones";
                $resultados = self::obtenerRegistros($sqlstr, []);

                $usuarios = [];
                foreach ($resultados as $row) {
                    $usuarios[] = [
                        'usuario_id' => $row['usuario_id'],
                        'nombre_usuario' => $row['usuario_id']
                    ];
                }
                return $usuarios;
            } catch (\Exception $e2) {
                error_log("Error en consulta fallback: " . $e2->getMessage());
                return [];
            }
        }
    }

    public static function obtenerEstadisticas($usuario_id)
    {
        try {
            $sqlstr = "SELECT 
                        COALESCE(SUM(CASE WHEN estado = 'completada' THEN total ELSE 0 END), 0) as total_gastado,
                        COALESCE(SUM(CASE WHEN estado = 'completada' THEN 1 ELSE 0 END), 0) as completadas,
                        COALESCE(SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END), 0) as pendientes,
                        COALESCE(SUM(CASE WHEN estado = 'cancelada' THEN 1 ELSE 0 END), 0) as canceladas
                      FROM transacciones 
                      WHERE usuario_id = :usuario_id";
            return self::obtenerUnRegistro($sqlstr, ["usuario_id" => $usuario_id]);
        } catch (\Exception $e) {
            error_log("Error en obtenerEstadisticas: " . $e->getMessage());
            return [
                'total_gastado' => 0,
                'completadas' => 0,
                'pendientes' => 0,
                'canceladas' => 0
            ];
        }
    }
}
