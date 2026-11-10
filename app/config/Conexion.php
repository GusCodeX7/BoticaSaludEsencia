<?php
require_once __DIR__ . '/config.php';

class Conexion
{
    // Crea y retorna la conexión PDO a MySQL.
    public static function conectar(): PDO
    {
        $cadena = 'mysql:host=' . DB_HOST .
                  ';dbname=' . DB_NAME .
                  ';charset=utf8mb4';

        return new PDO($cadena, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}
