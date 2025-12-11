<?php

namespace Dao\Catalogo;

use PDO;
use PDOException;

class Telefonos
{
    private $conexion;

    public function __construct()
    {
        $this->conectar();
    }

    private function conectar()
    {
        try {
            $this->conexion = new PDO(
                "mysql:host=127.0.0.1;dbname=ecommerce;charset=utf8",
                "root",
                ""
            );
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Error en conexión BD: " . $e->getMessage());
            $this->conexion = null;
        }
    }

    public function obtenerTodos()
    {
        if (!$this->conexion) return [];

        try {
            $sql = "SELECT 
                    productId AS id, 
                    productName AS nombre, 
                    productDescription AS descripcion, 
                    productPrice AS precio, 
                    COALESCE(productImgUrl, productImage) AS imagen, 
                    productStock AS disponibilidad 
                FROM products 
                WHERE productStatus = 'ACT' 
                ORDER BY productId ASC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtener productos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id)
    {
        if (!$this->conexion) return null;

        try {
            $sql = "SELECT 
                        productId AS id, 
                        productName AS nombre, 
                        productDescription AS descripcion, 
                        productPrice AS precio, 
                        productImgUrl AS imagen, 
                        productStock AS disponibilidad 
                    FROM products 
                    WHERE productId = :id AND productStatus = 'ACT'";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtener producto: " . $e->getMessage());
            return null;
        }
    }
}