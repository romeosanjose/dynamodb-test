<?php
// require_once '../config/config.php';
require '../vendor/autoload.php';

$dynamodb = $client;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['key'])) {
    $key = $_GET['key'];

    try {
        $result = $dynamodb->getItem([
            'TableName' => '' . $config['aws']['table_name'],
            'Key' => [
                'testpartition' => ['S' => $key],
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