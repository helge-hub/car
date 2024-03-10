<?php
require_once('vendor/autoload.php'); // Load the Dotenv library

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__); // Path to your .env file
$dotenv->load();

$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$database = $_ENV['DB_NAME'];

// Create a new mysqli connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_errno) {
    die("Failed to connect to MySQL: " . $conn->connect_error);
} else {
  
}
?>
