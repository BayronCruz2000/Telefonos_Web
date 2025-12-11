<?php

namespace Dao\Clientes;

use Dao\Table;

class Clientes extends Table
{
    public static function obtenerClientes(): array
    {
        $sqlstr = "SELECT * from clientes;";
        return  self::obtenerRegistros($sqlstr, []);
    }
    public static function obtenerClientePorCodigo(string $codigo): array
    {
        $sqlstr = "SELECT * from clientes where codigo=:codigo;";
        return self::obtenerUnRegistro($sqlstr, ["codigo" => $codigo]);
    }
    public static function crearCliente(
        string $codigo,
        string $nombre,
        string $direccion,
        string $correo,
        string $telefono,
        string $estado,
        int $evaluacion
    ) {
        $insSql = "INSERT INTO clientes (codigo, nombre, direccion, correo, telefono, estado, evaluacion)
            values (:codigo, :nombre, :direccion, :correo, :telefono, :estado, :evaluacion);";

        $newInsertData = [
            "codigo" => $codigo,
            "nombre" => $nombre,
            "direccion" => $direccion,
            "correo" => $correo,
            "telefono" => $telefono,
            "estado" => $estado,
            "evaluacion" => $evaluacion
        ];

        return self::executeNonQuery($insSql, $newInsertData);
    }

    public static function actualizarCliente(
        string $codigo,
        string $nombre,
        string $direccion,
        string $correo,
        string $telefono,
        string $estado,
        int $evaluacion
    ) {
        $updSql = "UPDATE clientes set nombre=:nombre, direccion=:direccion, correo=:correo,
           telefono=:telefono, estado=:estado, evaluacion=:evaluacion
           where codigo = :codigo;";

        $newUpdateData = [
            "codigo" => $codigo,
            "nombre" => $nombre,
            "direccion" => $direccion,
            "correo" => $correo,
            "telefono" => $telefono,
            "estado" => $estado,
            "evaluacion" => $evaluacion
        ];

        return self::executeNonQuery($updSql, $newUpdateData);
    }

    public static function eliminarCliente(string $codigo)
    {
        $delSql = "DELETE from clientes where codigo=:codigo;";
        $delParams = [
            "codigo" => $codigo
        ];
        return self::executeNonQuery($delSql, $delParams);
    }
}
