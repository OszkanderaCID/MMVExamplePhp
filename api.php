<?php

// Set up a simple API key for demonstration purposes
define('API_KEY', 'd9~d2llk,*-d7dd0.s2;[p5f');

// Helper function to send JSON responses
function sendJsonResponse($statusCode, $data = [])
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Process the POST request to /api/data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/data') {
    // Check for the API key in the headers
    $headers = getallheaders();
    if (!isset($headers['X-API-KEY']) || $headers['X-API-KEY'] !== API_KEY) {
        sendJsonResponse(401, ['error' => 'Unauthorized - Invalid API key']);
    }

    // Validate the Content-Type header
    if ($_SERVER['CONTENT_TYPE'] !== 'application/xml') {
        sendJsonResponse(400, ['error' => 'Invalid Content-Type. Expected application/xml']);
    }

    // Read and parse the XML body
    $rawData = file_get_contents('php://input');
    if (empty($rawData)) {
        sendJsonResponse(400, ['error' => 'Request body is empty']);
    }

    // Attempt to parse the XML data
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($rawData);
    if ($xml === false) {
        sendJsonResponse(400, ['error' => 'Invalid XML data']);
    }

    // Process the XML data (custom logic goes here)
    // For example, save it to a file or a database
    $filePath = 'data.xml';
    if (file_put_contents($filePath, $rawData) === false) {
        sendJsonResponse(500, ['error' => 'Failed to save data']);
    }

    // Respond with success
    sendJsonResponse(200, ['message' => 'Data has been successfully saved']);
}

// Handle invalid endpoints or methods
sendJsonResponse(404, ['error' => 'Endpoint not found']);