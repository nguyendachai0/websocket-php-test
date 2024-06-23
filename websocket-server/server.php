<?php

// Load Composer's autoload file to include all dependencies
require __DIR__ . '/../vendor/autoload.php';

// Import necessary classes from the Ratchet library
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Define the Chat class that implements MessageComponentInterface to handle WebSocket events
class Chat implements MessageComponentInterface
{
    protected $clients;

    // Constructor to initialize the clients storage
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    // Called when a new connection is opened
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn); // Add the new connection to the clients storage
        echo "New connection! ({$conn->resourceId})\n"; // Print a message to the console
    }

    // Called when a message is received from a client
    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Print a message indicating the sender and the message
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s' . "\n",
            $from->resourceId,
            $msg,
            count($this->clients) - 1,
            (count($this->clients) - 1 == 1 ? '' : 's')
        );

        // Loop through all connected clients
        foreach ($this->clients as $client) {
            // Send the message to all clients except the sender
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }
    // Called when a connection is closed
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn); // Remove the connection from the clients storage
        echo "Connection {$conn->resourceId} has disconnected\n"; // Print a message to the console
    }

    // Called when an error occurs
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n"; // Print the error message to the console
        $conn->close(); // Close the connection
    }
}

// Create the WebSocket server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat() // Pass the Chat class to handle WebSocket events
        )
    ),
    8080 // Listen on port 8080
);

echo "WebSocket server running at ws://localhost:8080\n"; // Print a message indicating the server is running

$server->run(); // Run the server
