<?php
require 'config/config.php';

use Aws\DynamoDb\DynamoDbClient;

$client = new DynamoDbClient([
    'region' => $config['region'],
    'version' => 'latest',
    'credentials' => [
        'key' => $config['aws_access_key_id'],
        'secret' => $config['aws_secret_access_key'],
    ],
]);

// Example usage
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include 'read.php';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'write.php';
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>