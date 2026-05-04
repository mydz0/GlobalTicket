<?php
//llamada a la base de datos
require_once dirname(__FILE__) . '/../Model/db.php';

class useController
{

    /*
     * /
     * @var
     */

    // Atributo privado: conexión a la BD
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConexion();
    }

    public function register($datos, $archivos): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //para ver que las contraseñas coincidan
            if ($datos['password'] !== $datos['confirm-password']) {
                header("Location: registerUser.php?error=password");
                exit();
            }

            //validacion formato mail
            if (!filter_var($datos['mail'], FILTER_VALIDATE_EMAIL)) {
                header("Location: registerUser.php?error=email");
                exit();
            }

            //validacion username min 3 caracteres y solo letras, numeros y underdash
            if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $datos['username'])) {
                header("Location: registerUser.php?error=username");
                exit();
            }

            //la foto:
            $foto = null;
            if (isset($archivos['photo']) && $archivos['photo']['error'] === UPLOAD_ERR_OK) {
                $directorioSubida = __DIR__ . "/../uploads/";
                if (!is_dir($directorioSubida)) {
                    mkdir($directorioSubida, 0777, true);
                }
                $nombreArchivo = time() . "_" . basename($archivos['photo']['name']);
                $rutaFinal = $directorioSubida . $nombreArchivo;
                if (move_uploaded_file($archivos['photo']['tmp_name'], $rutaFinal)) {
                    $foto = $nombreArchivo;
                }
            }

            //encriptación de contraseña
            $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, surname, mail, cellphone, username, password, photo)
                    VALUES (:name, :surname, :mail, :cellphone, :username, :password, :photo)";
            $stmt = $this->connection->prepare($sql);

            try {
                $stmt->execute([
                    ':name'      => $datos['name'],
                    ':surname'   => $datos['surname'],
                    ':mail'      => $datos['mail'],
                    ':cellphone' => $datos['cellphone'],
                    ':username'  => $datos['username'],
                    ':password'  => $passwordHash,
                    ':photo'     => $foto,
                ]);
                header("Location: /GlobalTicket/View/home/home.php");
                exit();
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') {
                    header("Location: registerUser.php?error=email_exists");
                } else {
                    header("Location: registerUser.php?error=db_error");
                }
                exit();
            }
        }
    }



    public function registerDisco($datos, $archivos): void
    {
        if ($datos['password'] !== $datos['confirm-password']) {
            header("Location: /GlobalTicket/View/signIn/discography/discoSignIn.php?error=password");
            exit();
        }

        if (!filter_var($datos['mail'], FILTER_VALIDATE_EMAIL)) {
            header("Location: /GlobalTicket/View/signIn/discography/discoSignIn.php?error=email");
            exit();
        }

        //encriptación de contraseña
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

        $foto = null;
        if (isset($archivos['photo']) && $archivos['photo']['error'] === UPLOAD_ERR_OK) {
            $directorioUploads = __DIR__ . "/../uploads/";
            if (!is_dir($directorioUploads)) mkdir($directorioUploads, 0777, true);
            $fileName = time() . "_" . basename($archivos['photo']['name']);
            $rutaFinal = $directorioUploads . $fileName;
            if (move_uploaded_file($archivos['photo']['tmp_name'], $rutaFinal)) {
                $foto = $fileName;
            }
        }

        $sql = "INSERT INTO discographies (name, cif, mail, cellphone, adress, password, photo, role)
                VALUES (:name, :cif, :mail, :cellphone, :adress, :password, :photo, 'disco')";
        $stmt = $this->connection->prepare($sql);

        try {
            $stmt->execute([
                ':name'      => $datos['name'],
                ':cif'       => $datos['cif'],
                ':mail'      => $datos['mail'],
                ':cellphone' => $datos['cellphone'],
                ':adress'    => $datos['adress'],
                ':password'  => $passwordHash,
                ':photo'     => $foto,
            ]);
            header("Location: /GlobalTicket/View/home/home.php");
            exit();
        } catch (PDOException) {
            header("Location: /GlobalTicket/View/signIn/discography/discoSignIn.php?error=error_registro");
            exit();
        }
    }



    //  REGISTER  (req. 4.4 — nombre exacto del diagrama UML)
    //  Decide si registrar usuario normal o discográfica según $_POST['type']
    public function login($datos): void
    {

        session_start();
        if ($datos['tipo'] === 'user') {
            //buscar usuario por username
            $stmt = $this->connection->prepare("SELECT id, username, password, role FROM users WHERE username = ? "); //and password = ? quitar para q la contraseña encriptada pueda comprobar
            $stmt->execute([$datos['username']]);
            $usuario = $stmt->fetch();
            $stmt = null;

            //comprobar que existe y que coincida la contraseña
            if ($usuario && password_verify($datos['password'],  $usuario['password'])) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['username'] = $usuario['username'];
                $_SESSION['role'] = $usuario['role'];

                header("Location: ../profile/perfilUser.php");
                // header("Location: /GlobalTicket/View/profile/perfilUser.php");
                exit();
            } else {

                header("Location: ../login/login.php?error=credenciales");
                exit();
            }
        } else {
            //buscar discografia por cif
            $stmt = $this->connection->prepare("SELECT id, name, password, role FROM discographies WHERE cif = ?");
            $stmt->execute([$datos['cif']]);
            $disco = $stmt->fetch();
            $stmt = null;

            if ($disco && password_verify($datos['password'], $disco['password'])) {
                $_SESSION['user_id'] = $disco['id'];
                $_SESSION['username'] = $disco['name'];
                $_SESSION['role'] = $disco['role'];
                header("Location: ../profile/perfilDisco.php");
                exit();
            } else {
                header("Location: ../login/login.php?error=credenciales");
                exit();
            }
        }
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /GlobalTicket/View/login/login.php");
        exit();
    }
}
