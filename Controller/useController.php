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

    // REGISTRO USUARIO: 
    public function register($datos, $archivos): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return; {

            //validación
            if (empty($datos['username']) || empty($datos['password']) || empty($datos['mail'])) {
                header("Location: registerUser.php?error=empty");
                exit();
            }

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

            //FOTO:
            $foto = $this->subirFoto($archivos);

            //encriptación de contraseña
            $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, surname, mail, cellphone, username, password, photo)
                    VALUES (:name, :surname, :mail, :cellphone, :username, :password, :photo)";

            $stmt = $this->connection->prepare($sql);

            try {
                $stmt->execute([
                    ':name'      => $datos['name'] ?? null,
                    ':surname'   => $datos['surname'] ?? null,
                    ':mail'      => $datos['mail'],
                    ':cellphone' => $datos['cellphone'] ?? null,
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


    // REGISTRO DISCO:
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

        //Subir foto:
        $foto = $this->subirFoto($archivos);

        //encriptación de contraseña
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

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
            $sql = "SELECT id, username, password, role FROM users WHERE username = :username";
            $stmt = $this->connection->prepare($sql); //and password = ? quitar para q la contraseña encriptada pueda comprobar
            $stmt->execute([': username' => $datos['username']]);

            $usuario = $stmt->fetch();

            //comprobar que existe y que coincida la contraseña
            if ($usuario && password_verify($datos['password'],  $usuario['password'])) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['username'] = $usuario['username'];
                $_SESSION['role'] = $usuario['role'];

                header("Location: ../profile/perfilUser.php");
                exit();

            } else {

                $sql = "SELECT id, name, password, role FROM discographies WHERE cif = :cif";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([':cif' => $datos['cif']]);

                $disco = $stmt ->fetch();
                
                if ($disco && password_verify($datos['password'], $disco['password'])) {
                $_SESSION['user_id'] = $disco['id'];
                $_SESSION['username'] = $disco['name'];
                $_SESSION['role'] = $disco['role'];

                header("Location: ../login/login.php?error=credenciales");
                exit();
            }


        } 
        }

        header("Location: ../login/login.php?error=credenciales");
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


    // Para subir una img al servidor y que devuelva el nom del archivo 
    // recibe el array de archivo, el $_FILES
    private function subirFoto($archivos): ?string {

        // esto comprubea si existe el archivo
        if (!isset($archivos['photo']) || $archivos['photo']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Aqui le decimos donde guardarla la img 
        $directorio = __DIR__ . "/../uploads/";

        // Y si no existe, lo crea 
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombre = time() . "_" . basename($archivos['photo']['name']);
        $ruta = $directorio . $nombre;

        if (move_uploaded_file($archivos['photo']['tmp_name'], $ruta)) {
            return $nombre;
        }

        return null;
        
    }
}
