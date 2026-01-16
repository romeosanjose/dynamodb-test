<?php
require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

$config = require '../config/config.php';

$dynamodb = new DynamoDbClient([
    'region' => $config['region'],
    'version' => 'latest',
    'credentials' => [
        'key' => $config['aws_access_key_id'],
        'secret' => $config['aws_secret_access_key'],
    ],
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['YourPrimaryKey']) && isset($data['OtherAttr'])) {
        $item = [
            'YourPrimaryKey' => ['S' => $data['YourPrimaryKey']],
            'OtherAttr' => ['S' => $data['OtherAttr']],
        ];

        try {
            $result = $dynamodb->putItem([
                'TableName' => $config['table_name'],
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