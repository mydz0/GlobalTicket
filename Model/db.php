<?php
class Database
{
    // Instancia única (patrón Singleton)
    private static $instancia = null;

    // Datos de conexión
    private string $host = "localhost";
    private string $usuario = "root";
    private string $password = "";
    private string $baseDatos = "mp0487_globaltickets";
    private int    $port      = 3306;
    // private string $host      = 'trolley.proxy.rlwy.net';
    // private string $usuario   = 'root';
    // private string $password  = 'QQSZVHcQKPqOKLCyGlokKZzoBsHaTkqs';
    // private string $baseDatos = 'railway';
    // private int    $port      = 32336;

    // Objeto de conexión mysqli
    private mysqli $conexion;

    // Constructor privado: solo se puede llamar desde esta misma clase
    private function __construct()
    {
        $this->conexion = new mysqli(
            $this->host,
            $this->usuario,
            $this->password,
            $this->baseDatos,
            $this->port
        );

        // Comprobamos si hay error de conexión
        if ($this->conexion->connect_error) {
            die('Error de conexión: ' . $this->conexion->connect_error);
        }

        // Charset para acentos y caracteres especiales
        $this->conexion->set_charset('utf8mb4');
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
    public function getConexion(): mysqli
    {
        return $this->conexion;
    }
}
