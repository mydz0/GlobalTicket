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
                error_log("Foto subida con exito a: " . $rutaFinal);
            }
        }

        //insertar usuario en la base de datos
        $passwordHash = $datos['password'];

        //nombre de la foto (null si no hay foto)
        $foto = isset($nombreArchivo) ? $nombreArchivo : null;

        //prepared statement evitar sql injection
        $stmt = $this->connection->prepare("INSERT INTO users (name, surname, mail, cellphone, username, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssss", $datos['name'], $datos['surname'], $datos['mail'], $datos['cellphone'], $datos['username'], $passwordHash, $foto);

        mysqli_report(MYSQLI_REPORT_OFF);

        if ($stmt->execute()) {
            header("Location: /GlobalTicket/View/home/home.php");
            exit();
        }
        if ($this->connection->errno === 1062) {
            header("Location: registerUser.php?error=email_exists");
        } else {
            header("Location: registerUser.php?error=db_error");
        }
        exit();
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

        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

        $foto = null;
        if (isset($archivos['photo']) && $archivos['photo']['error'] === UPLOAD_ERR_OK) {
            $directorioUploads = "../../uploads/";
            if (!is_dir($directorioUploads)) mkdir($directorioUploads, 0777, true);
            $fileName = time() . "_" . basename($archivos['photo']['name']);
            $rutaFinal = $directorioUploads . $fileName;
            if (move_uploaded_file($archivos['photo']['tmp_name'], $rutaFinal)) {
                $foto = $fileName;
            }
        }

        $stmt = $this->connection->prepare("INSERT INTO discographies (name, cif, mail, cellphone, adress, password, photo, role)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'disco')");

        $stmt->bind_param(
            "sssssss",
            $datos['name'],
            $datos['cif'],
            $datos['mail'],
            $datos['cellphone'],
            $datos['adress'],
            $passwordHash,
            $foto
        );

        if ($stmt->execute()) {
            header("Location: /GlobalTicket/View/home/home.php");
            exit();
        } else {
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
            $stmt = $this->connection->prepare("SELECT id, username, password, role FROM users WHERE username = ? ");
            $stmt->bind_param("s", $datos['username']);
            $stmt->execute();
            $usuario = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            //comprobar que existe y que coincida la contraseña
            if ($usuario && $datos['password'] === $usuario['password']) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['username'] = $usuario['username'];
                $_SESSION['role'] = $usuario['role'];

                header("Location: ../profile/perfilUser.php");
                exit();
            } else {
                header("Location: ../login/login.php?error=credenciales");
                exit();
            }
        } else {
            //buscar discografia por cif
            $stmt = $this->connection->prepare("SELECT id, name, password, role FROM discographies WHERE cif = ?");
            $stmt->bind_param("s", $datos['cif']);
            $stmt->execute();
            $disco = $stmt->get_result()->fetch_assoc();
            $stmt->close();

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

    public function deleteAccount(): void
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /GlobalTicket/View/login/login.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /GlobalTicket/View/home/home.php");
            exit();
        }

        $role   = $_SESSION['role'] ?? '';
        $tables = ['user' => 'users', 'disco' => 'discographies'];

        if (!isset($tables[$role])) {
            header("Location: /GlobalTicket/View/login/login.php");
            exit();
        }

        $table = $tables[$role];
        $id    = $_SESSION['user_id'];

        $stmt = $this->connection->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        session_unset();
        session_destroy();

        header("Location: /GlobalTicket/View/home/home.php");
        exit();
    }

    public function changePassword($datos): void
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /GlobalTicket/View/login/login.php");
            exit();
        }

        $role   = $_SESSION['role'] ?? '';
        $tables = ['user' => 'users', 'disco' => 'discographies'];

        if (!isset($tables[$role])) {
            header("Location: /GlobalTicket/View/login/login.php");
            exit();
        }

        $table    = $tables[$role];
        $redirect = $role === 'user'
            ? '/GlobalTicket/View/profile/editProfileUser.php'
            : '/GlobalTicket/View/profile/editProfileDisco.php';

        if (
            empty($datos['current-password']) ||
            empty($datos['new-password']) ||
            empty($datos['confirm-password'])
        ) {
            header("Location: $redirect?error=missing_fields");
            exit();
        }

        if ($datos['new-password'] !== $datos['confirm-password']) {
            header("Location: $redirect?error=password_mismatch");
            exit();
        }

        if (strlen($datos['new-password']) < 6) {
            header("Location: $redirect?error=password_short");
            exit();
        }

        $id   = $_SESSION['user_id'];
        $stmt = $this->connection->prepare("SELECT password FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row || !password_verify($datos['current-password'], $row['password'])) {
            header("Location: $redirect?error=wrong_password");
            exit();
        }

        $newHash = password_hash($datos['new-password'], PASSWORD_DEFAULT);
        $stmt2   = $this->connection->prepare("UPDATE $table SET password = ? WHERE id = ?");
        $stmt2->bind_param("si", $newHash, $id);
        $stmt2->execute();
        $stmt2->close();

        header("Location: $redirect?success=1");
        exit();
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
