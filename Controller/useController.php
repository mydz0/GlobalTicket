<?php
//llamada a la base de datos
require_once '../Model/db.php';

class useController
{

    /*
     * /
     * @var 
     */

    // Atributo privado: conexión a la BD
    private mysqli $connection;

    public function __construct()
    {
        // Obtenemos la conexión mysqli desde el Singleton de db.php
        $this->connection = Database::getInstance()->getConexion();
    }

    public function register($datos, $archivos): void
    {

        //para ver que las contraseñas coincidan
        if ($datos['password'] !== $datos['confirm-password']) {

            //ponemos el error por la ruta porque no deja poner return (usamoos el header)
            header("Location: ../View/signIn/registerUser.php?error=password");
            exit();
        }

        //la foto: 
        if (isset($archivos['photo']) && $archivos['photo']['error'] === UPLOAD_ERR_OK) {

            //creamos la carpeta para las fotos:
            $directorioSubida = "../../uploads/"; //la ruta donde se guarda la img

            if (!is_dir($directorioSubida)) {
                mkdir($directorioSubida, 0777, true);
            }

            //el nom del archivo y donde lo guardamos:
            $nombreArchivo = time() . "_" . basename($archivos['photo']['name']);
            $rutaFinal = $directorioSubida . $nombreArchivo;

            if (move_uploaded_file($archivos['photo']['tmp_name'], $rutaFinal)) {

                //cuando tengamos la base de datos esto lo deberiamos de cambiar, se guardaría en una variable
                error_log("Foto subida con exito a: " . $rutaFinal);
            }
        }

        //insertar usuario en la base de datos
        //crear contraseña antes de guardar
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

        //nombre de la foto (null si no hay foto)
        $foto = isset($nombreArchivo) ? $nombreArchivo : null;

        //prepared statement evitar sql injection
        $stmt = $this->connection->prepare("INSERT INTO users (name, surname, mail, cellphone, username, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssss", $datos['name'], $datos['surname'], $datos['mail'], $datos['cellphone'], $datos['username'], $passwordHash, $foto);

        if ($stmt->execute()) {
            header("Location: ../../home/home.html");
            exit();
        } else {
            header("Location: ../user/registerUser.php?error=error_registro");
            exit();
        }

        $stmt->close();
        
    }



    //  REGISTER  (req. 4.4 — nombre exacto del diagrama UML)
    //  Decide si registrar usuario normal o discográfica según $_POST['type']
    public function login(): void {}

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }
}
