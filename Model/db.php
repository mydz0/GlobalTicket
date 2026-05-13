<?php
class Database
{
    // Instancia única (patrón Singleton)
    private static ?self $instancia = null;

    // Datos de conexión
    private string $host = "localhost";
    private string $usuario = "root";
    private string $password = "";
    private string $baseDatos = "globaltickets";
    //private $port      = 3306;

    private  ?PDO $conexion = null;

    public function getConexion(): PDO
    {
        if ($this->conexion === null) {

            try {
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->baseDatos . ";charset=utf8mb4";

                $opciones = [
                    // Modo de errores: lanzar excepciones
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

                    // Modo de fetch por defecto: array asociativo
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

                    // No emular prepared statements (más seguro)
                    PDO::ATTR_EMULATE_PREPARES => false,

                    // Conexiones persistentes (reutilizar conexiones)
                    PDO::ATTR_PERSISTENT => true,

                    // Timeout de conexión (segundos)
                    PDO::ATTR_TIMEOUT => 5
                ];

                $this->conexion = new PDO($dsn, $this->usuario, $this->password, $opciones);
            } catch (PDOException $e) {
                throw new PDOException("Error de conexión: " . $e->getMessage());
            }
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
}
