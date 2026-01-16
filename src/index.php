<?php
$config = require __DIR__ . '/../config/config.php';
require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

$client = new DynamoDbClient([
    'region' => $config['aws']['region'],
    'version' => 'latest',
    'credentials' => [
        'key' => $config['aws']['key'],
        'secret' => $config['aws']['secret'],
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