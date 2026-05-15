<?php
session_start();
require_once dirname(__FILE__) . '/../Model/db.php';

class EventController
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConexion();
    }

    public function createEvent(array $datos, array $archivos): void
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['disco', 'discography'])) {
            header("Location: /GlobalTicket/View/login/login.php");
            exit();
        }

        if (empty($datos['name']) || empty($datos['date']) || empty($datos['location'])) {
            header("Location: /GlobalTicket/View/event/addEvent.php?error=empty");
            exit();
        }

        $image = $this->subirImagen($archivos);

        $sql = "INSERT INTO events
                    (name, date, location, description, artist, price, capacity, image, latitude, longitude, discography_id)
                VALUES
                    (:name, :date, :location, :description, :artist, :price, :capacity, :image, :latitude, :longitude, :discography_id)";

        $stmt = $this->connection->prepare($sql);

        try {
            $stmt->execute([
                ':name'           => htmlspecialchars($datos['name']),
                ':date'           => $datos['date'],
                ':location'       => htmlspecialchars($datos['location']),
                ':description'    => htmlspecialchars($datos['description'] ?? ''),
                ':artist'         => htmlspecialchars($datos['artist'] ?? ''),
                ':price'          => (float)($datos['price'] ?? 0),
                ':capacity'       => (int)($datos['capacity'] ?? 100),
                ':image'          => $image,
                ':latitude'       => !empty($datos['latitude'])  ? (float)$datos['latitude']  : null,
                ':longitude'      => !empty($datos['longitude']) ? (float)$datos['longitude'] : null,
                ':discography_id' => $_SESSION['user_id'],
            ]);

            header("Location: /GlobalTicket/View/profile/perfilDisco.php?success=event_created");
            exit();

        } catch (PDOException $e) {
            header("Location: /GlobalTicket/View/event/addEvent.php?error=db_error");
            exit();
        }
    }

    public function createReservation(array $datos): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
            header("Location: /GlobalTicket/View/login/login.php");
            exit();
        }

        $eventId = (int)($datos['event_id'] ?? 0);
        if ($eventId <= 0) {
            header("Location: /GlobalTicket/View/home/home.php");
            exit();
        }

        $sql = "INSERT INTO reservations (user_id, event_id, quantity)
                VALUES (:user_id, :event_id, 1)
                ON DUPLICATE KEY UPDATE quantity = quantity";

        $stmt = $this->connection->prepare($sql);

        try {
            $stmt->execute([
                ':user_id'  => $_SESSION['user_id'],
                ':event_id' => $eventId,
            ]);

            header("Location: /GlobalTicket/View/profile/perfilUser.php?success=reserved");
            exit();

        } catch (PDOException $e) {
            header("Location: /GlobalTicket/View/profile/perfilUser.php?error=reservation_failed");
            exit();
        }
    }

    private function subirImagen(array $archivos): ?string
    {
        if (!isset($archivos['image']) || $archivos['image']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($archivos['image']['type'], $allowed)) {
            return null;
        }

        $directorio = dirname(__DIR__) . "/uploads/events/";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombre = time() . "_" . basename($archivos['image']['name']);
        $ruta   = $directorio . $nombre;

        if (move_uploaded_file($archivos['image']['tmp_name'], $ruta)) {
            return $nombre;
        }

        return null;
    }
}

// ── Dispatch ──────────────────────────────────────────
$controller = new EventController();
$action = $_POST['action'] ?? '';

if ($action === 'createEvent') {
    $controller->createEvent($_POST, $_FILES);
} elseif ($action === 'createReservation') {
    $controller->createReservation($_POST);
} else {
    header("Location: /GlobalTicket/View/home/home.php");
    exit();
}
