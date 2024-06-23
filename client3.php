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

    echo "Connected to WebSocket server. Type your messages below:\n";

    // Open a loop to keep the connection alive
    while (true) {
        // Check for user input
        $input = fgets(STDIN);
        if (trim($input) !== '') {
            // Send the user input as a message to the server
            $client->send(trim($input));
        }

        // Try to receive a message from the WebSocket server
        // try {
        //     $message = $client->receive();
        //     echo "Received message from server: $message\n"; // Print the received message
        // } catch (Exception $e) {
        //     // Print the error message if an exception occurs
        //     echo "Error receiving message: " . $e->getMessage() . "\n";
        // }
    }
} catch (Exception $e) {
    // Print the error message if an exception occurs
    echo "Error: " . $e->getMessage();
}


    // Close the WebSocket connection
    // $client->close();
