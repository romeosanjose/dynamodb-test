<?php
require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

$config = require '../config/config.php';

$dynamodb = $client;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['testpartition']) && isset($data['is_active'])) {
        $item = [
            'testpartition' => ['S' => $data['testpartition']],
            'is_active' => ['BOOL' => $data['is_active']],
        ];

        try {
            $result = $dynamodb->putItem([
                'TableName' => '' . $config['aws']['table_name'],
                'Item' => $item,
            ]);
            echo json_encode(['status' => 'success']);
        } catch (AwsException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid input']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>