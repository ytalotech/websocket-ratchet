<?php

require 'vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class NotificationServer implements MessageComponentInterface
{

    protected $clients;
    private $pdo;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;

        $this->pdo = new PDO(
            'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! - ({$conn->resourceId})\n";
        $this->sendNotifications($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Mensagem recebida de {$from->resourceId}: " . $msg . "\n";

        $data = json_decode($msg, true);

        if (isset($data['type']) && $data['type'] === 'new_comment') {
            echo "Processando novo comentário: " . $data['subject'] . "\n";
            $statement = $this->pdo->prepare("INSERT INTO comments (comment_subject, comment_text, comment_status) VALUES (?, ?, 0)");
            $statement->execute([$data['subject'], $data['comment']]);
            echo "Comentário salvo no banco de dados\n";
            $this->broadcastNotifications();
        }

        if (isset($data['type']) && $data['type'] === 'mark_as_read') {
            echo "Marcando comentário {$data['comment_id']} como lido\n";
            $this->markNotificationAsRead($data['comment_id']);
            $this->broadcastNotifications(); // Refresh for all users
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function broadcastNotifications()
    {
        foreach ($this->clients as $client) {
            $this->sendNotifications($client);
        }
    }

    private function markNotificationAsRead($commentId)
    {
        $statement = $this->pdo->prepare("UPDATE comments SET comment_status = 1 WHERE comment_id = ?");
        $statement->execute([$commentId]);
    }

    private function sendNotifications(ConnectionInterface $conn)
    {
        $statement = $this->pdo->query("SELECT * FROM comments ORDER BY comment_id DESC LIMIT 5");

        $notifications = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement = $this->pdo->query("SELECT COUNT(*) AS unread_count FROM comments WHERE comment_status = 0");

        $unreadCount = $statement->fetch(PDO::FETCH_ASSOC)["unread_count"];

        $response = [
            'type' => 'notification',
            'notifications' => $notifications,
            'unread_count'    =>    $unreadCount
        ];

        $conn->send(json_encode($response));
    }
}

// Start the WebSocket server

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new NotificationServer()
        )
    ),
    8080
);

echo "WebSocket server started on port 8080\n";
$server->run();
