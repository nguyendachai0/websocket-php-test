<?php

// Load Composer's autoload file to include all dependencies
require __DIR__ . '/vendor/autoload.php';

// Import the WebSocket Client class
use WebSocket\Client;

try {
    // Create a new WebSocket client and connect to the server at ws://localhost:8080
    $client = new Client("ws://localhost:8080");

    // Send an initial message to the WebSocket server
    $client->send("Hello Server!");

    echo "Connected to WebSocket server.\n";

    // Use non-blocking mode for receiving messages

    while (true) {
        // Non-blocking check for server messages
        try {
            $message = $client->receive();
            if ($message) {
                echo "Received message from server: $message\n";
            }
        } catch (Exception $e) {
            // Handle exceptions related to receiving messages (typically timeout
        }
    }

    // Close the WebSocket connection
    echo "Connection closed.\n";
} catch (Exception $e) {
    // Print the error message if an exception occurs
    echo "Error: " . $e->getMessage() . "\n";
}
