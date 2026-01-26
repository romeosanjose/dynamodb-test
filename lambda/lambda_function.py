import json
import boto3
import os

dynamodb = boto3.resource('dynamodb')
table_name = os.environ['test_dynamo_db']

def lambda_handler(event, context):
    print("Received event:", json.dumps(event))
    table = dynamodb.Table(table_name)
    method = event.get('requestContext', {}).get('http', {}).get('method')  # Updated to match API Gateway v2 structure
    
    if not method:
        return {
            'statusCode': 400,
            'body': json.dumps({'error': 'Missing httpMethod in event'})
        }


    if method == 'GET':
        # Example: /item?key=SomeValue
        key = event['queryStringParameters']['key']
        response = table.get_item(Key={'testpartition': key})
        return {
            'statusCode': 200,
            'body': json.dumps(response.get('Item', {}))
        }
    elif method == 'POST':
        # Body: {"YourPrimaryKey": "SomeValue", "OtherAttr": "OtherValue"}
        item = json.loads(event['body'])
        table.put_item(Item=item)
        return {
            'statusCode': 200,
            'body': json.dumps({'status': 'success'})
        }
    else:
        return {
            'statusCode': 400,
            'body': json.dumps({'error': 'Unsupported method'})
        }