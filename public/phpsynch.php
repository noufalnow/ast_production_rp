<?php

date_default_timezone_set('Asia/Muscat');

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? '';

$apiKey = 'YOUR_API_KEY_HERE';
if ($authHeader !== 'Bearer ' . $apiKey) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Continue processing the request
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['table'], $input['primaryKey'], $input['rows'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}



header("Content-Type: application/json");


// Log file path
$logFile = __DIR__ . '/sync_log.txt';

// Function to log messages
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

// Log the incoming request
$inputJson = file_get_contents("php://input");
logMessage("Incoming request: $inputJson");

// MySQL Database connection settings
$host = 'localhost';
$dbname = 'astglesp_db_csol_ast'; // Replace with your database name
$username = 'astglesp_db_csol_ast';
$password = 'o8%1$?]z;=;+d$24j{';

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Set fetch mode to associative array
    logMessage("Database connection established successfully.");
} catch (PDOException $e) {
    $errorMessage = "Database connection failed: " . $e->getMessage();
    logMessage($errorMessage);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $errorMessage]);
    exit;
}

// Fetch input data
$data = json_decode($inputJson, true);
$tableName = $data['table'];
$rows = $data['rows'];
$primaryKey = $data['primaryKey']; // Get the primary key field name from the input

$successfulIds = [];
try {
    foreach ($rows as $row) {
        $placeholders = implode(", ", array_fill(0, count($row), "?"));
        $columns = implode(", ", array_keys($row));
        $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)
                ON DUPLICATE KEY UPDATE " . implode(", ", array_map(fn($col) => "$col = VALUES($col)", array_keys($row)));

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array_values($row))) {
            // Use the dynamic primary key field
            $successfulIds[] = $row[$primaryKey];
        }
    }
    $response = ["status" => "success", "successfulIds" => $successfulIds];
    logMessage("Response: " . json_encode($response));
    echo json_encode($response);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    logMessage("Error processing request: $errorMessage");
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $errorMessage]);
}
