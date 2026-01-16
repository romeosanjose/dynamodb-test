<?php
require_once '../config/config.php';

use Aws\DynamoDb\DynamoDbClient;

$dynamodb = new DynamoDbClient([
    'region' => AWS_REGION,
    'version' => 'latest',
    'credentials' => [
        'key' => AWS_ACCESS_KEY_ID,
        'secret' => AWS_SECRET_ACCESS_KEY,
    ],
]);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['key'])) {
    $key = $_GET['key'];

    try {
        $result = $dynamodb->getItem([
            'TableName' => DYNAMODB_TABLE_NAME,
            'Key' => [
                'YourPrimaryKey' => ['S' => $key],
            ],
        ]);

        if (isset($result['Item'])) {
            echo json_encode($result['Item']);
        } else {
            echo json_encode(['error' => 'Item not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>