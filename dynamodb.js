// dynamodb.js
// Simple DynamoDB read/write example using AWS SDK v3 for Node.js

const { DynamoDBClient, GetItemCommand, PutItemCommand } = require('@aws-sdk/client-dynamodb');

// Update these values or load from config as needed
const REGION = 'your-region'; // e.g., 'us-east-1'
const TABLE_NAME = 'your-table-name';
const client = new DynamoDBClient({ region: REGION });

// Write (putItem) example
async function writeItem(testpartition, is_active) {
    const params = {
        TableName: TABLE_NAME,
        Item: {
            'testpartition': { S: testpartition },
            'is_active': { BOOL: is_active },
        },
    };
    try {
        await client.send(new PutItemCommand(params));
        console.log('Write successful');
    } catch (err) {
        console.error('Write error:', err);
    }
}

// Read (getItem) example
async function readItem(testpartition) {
    const params = {
        TableName: TABLE_NAME,
        Key: {
            'testpartition': { S: testpartition },
        },
    };
    try {
        const data = await client.send(new GetItemCommand(params));
        if (data.Item) {
            console.log('Read result:', data.Item);
        } else {
            console.log('Item not found');
        }
    } catch (err) {
        console.error('Read error:', err);
    }
}

// Example usage:
// node dynamodb.js write mykey true
// node dynamodb.js read mykey

const [,, action, key, value] = process.argv;
if (action === 'write' && key && value !== undefined) {
    writeItem(key, value === 'true');
} else if (action === 'read' && key) {
    readItem(key);
} else {
    console.log('Usage: node dynamodb.js write <key> <true|false>');
    console.log('       node dynamodb.js read <key>');
}
