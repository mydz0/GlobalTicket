<?php

require_once dirname(__FILE__) . '/../Model/db.php';

class EventController 

{
    private PDO $connection;

    //CONSTRUCTOR: se conecta automaticamente a la base de datos 
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