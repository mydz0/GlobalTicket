<?php

require_once dirname(__FILE__) . '/../Model/db.php';

class EventController 

{
    private PDO $connection;

    //el contructor para conectarlo a la base de datos 
    public function __construct()
    {

        $this->connection = Database::getInstance()->getConexion();

    }

    //LEER TODOS LOS EVENTOS: los devuelve todos 
    public function getAllEvents(): array 
    {
        $sql = $this->connection->prepare($sql);
        $stmt->execute();

        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $events;
    }

    //LEER UNO EN ESPECIFICO: los busca por ID 
    public function getEventById(int $id): array|null
    {
        $sql = "SELECT * FROM events WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);

        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        return $event ?:null;

    }
}

public function createEvent(array $datos, array $archivos): void {
    if(empty($datos['name'])){
        header("Location: /GlobalTicket/View/event/addEvent.php?error=name");
        exit();
    }
}

//validar fecha
$fecha = $datos['event_date'] ?? '';
if(empty($fecha) || strtotime($fecha) === false || strtotime($fecha) < time()){
    header("Location: /GlobalTicket/View/event/addEvent.php?error=date");
        exit();
}

//validate location
if(empty($datos['location'])){
     header("Location: /GlobalTicket/View/event/addEvent.php?error=location");
        exit();
}

//validar descripcion 
$desc = trim($datos['description']);
if(strlen($desc) < 70 || strlen($desc)>158 ){
    header("Location: /GlobalTicket/View/event/addEvent.php?error=description");
        exit();
    
}

//subir foto si se ha enviado

$foto = null;
if(isset($archivos['photo']) && $archivos['photo']['error']=== UPLOAD_ERR_OK){
    $directiorioUploads = __DIR__ . "/../uploads/";
    if(!is_dir($directiorioUploads)){
        mkdir($directiorioUploads, 0777, true);
    }
    $fileName = time() . "_" . basename($archivos['photo']['name']);
    if(move_uploaded_file($archivos['photo']['tmp_name'], $directiorioUploads . $fileName)){
        $foto = $fileName;
    }
}

//insertar en la base de datos
$stmt = $this->connection->prepare(
    "INSERT INTO events (name, event_date, location, description, photo) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sssss", $datos['name'], $datos['event_date'], $datos['location'], $datos['description'], $foto);

    if ($stmt->execute()) {
        header("Location: /GlobalTicket/View/profile/perfilDisco.php");
        exit();
    }


    header("Location: /GlobalTicket/View/event/addEvent.php?error=db_error");
    exit();
