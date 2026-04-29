<?php
class Database
{
    // Instancia única (patrón Singleton)
    private static $instancia = null;

    // Datos de conexión
    private string $host = "localhost";
    private string $usuario = "root";
    private string $password = "";
    private string $baseDatos = "globaltickets";
    private $port      = 3306;

    private $conexion;

    public function getConexion()
    {
        $this->conexion = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->baseDatos . ";charset=utf8mb4";

            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $this->conexion = new PDO($dsn, $this->usuario, $this->password, $opciones);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }

        return $this->conexion;
    }

    // Método estático que devuelve la única instancia de la clase
    public static function getInstance(): self
    {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    // Devuelve el objeto mysqli para usarlo en las consultas
}
