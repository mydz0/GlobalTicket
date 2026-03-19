<?php
class useController
{
    /**yessamin
     * /
     * @var 
     */
    private $connection;

    public function __construct()
    {
        throw new \Exception('Not implemented');
    }

    public function register($datos, $archivos): ?string 
    {
        //para ver que las contraseñas coincidan
        if ($datos['password'] !== $datos['confirm-password']) {
            return "Las contraseñas no coinciden.";
        }

        //la foto: 
        if (isset($archivos['foto_perfil']) && $archivos['foto_perfil']['error'] === UPLOAD_ERR_OK) {

            //creamos la carpeta para las fotos:
            $directorioSubida = "../../uploads/"; //la ruta donde se guarda la img
            if (!is_dir($directorioSubida)) {
            mkdir($directorioSubida, 0777, true);

            //el nom del archivo y donde lo guardamos:
            $nombreArchivo = time() . "_" . basename($archivos['foto_perfil']['name']);
            $rutaFinal = $directorioSubida . $nombreArchivo;

            if (move_uploaded_file($archivos['foto_perfil']['tmp_name'], $rutaFinal)) {
                //cuando tengamos la base de datos esto lo deberiamos de cambiar, se guardaría en una variable
                error_log("Foto subida con éxito a: " . $rutaFinal);
    }
        }

        //con esto lo guardamos (luego tendremos q cambiarlo cuando tengamos la base de datos)
        $todoCorrecto = true; 

        //si todo esta bien (asumimos q si) pues te lo hace 
        if ($todoCorrecto) {
            header("Location: ../home/home.html"); 
            exit();
        } else {
            return "Error al procesar el registro.";
        }
    }    
    }  

    public function login(): void {}

    public function logout(): void {}

}
