// server.js
// Simple Express HTTP API for DynamoDB read/write using AWS SDK v3

const express = require('express');
const { DynamoDBClient, GetItemCommand, PutItemCommand } = require('@aws-sdk/client-dynamodb');
const bodyParser = require('body-parser');

// Update these values or load from config as needed
const REGION = 'ap-southeast-2'; // e.g., 'us-east-1'
const TABLE_NAME = 'test_dynamo_db';
const client = new DynamoDBClient({ 
    region: REGION,
    requestHandler : new NodeHttpHandler({
        httpAgent: new http.Agent({ keepAlive: true })
    })
 });

const app = express();
app.use(bodyParser.json());

// Write endpoint
app.post('/write', async (req, res) => {
    const { testpartition, is_active } = req.body;
    if (typeof testpartition !== 'string' || typeof is_active !== 'boolean') {
        return res.status(400).json({ error: 'Invalid input' });
    }
    const params = {
        TableName: TABLE_NAME,
        Item: {
            'testpartition': { S: testpartition },
            'is_active': { BOOL: is_active },
        },
    };
    try {
        await client.send(new PutItemCommand(params));
        res.json({ status: 'success' });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// Read endpoint
app.get('/read', async (req, res) => {
    const key = req.query.key;
    if (!key) {
        return res.status(400).json({ error: 'Missing key parameter' });
    }
    const params = {
        TableName: TABLE_NAME,
        Key: {
            'testpartition': { S: key },
        },
    };
    try {
        const data = await client.send(new GetItemCommand(params));
        if (data.Item) {
            res.json(data.Item);
        } else {
            res.status(404).json({ error: 'Item not found' });
        }
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
