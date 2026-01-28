// DynamoDBReadWrite.java
// Simple Java example for DynamoDB read/write using AWS SDK v2

import software.amazon.awssdk.regions.Region;
import software.amazon.awssdk.services.dynamodb.DynamoDbClient;
import software.amazon.awssdk.services.dynamodb.model.*;
import software.amazon.awssdk.auth.credentials.ProfileCredentialsProvider;
import java.util.HashMap;
import java.util.Map;

public class DynamoDBReadWrite {
    private static final String TABLE_NAME = "test_dynamo_db";
    private static final Region REGION = Region.AP_SOUTHEAST_2;
    private static final DynamoDbClient client = DynamoDbClient.builder()
            .region(REGION)
            .credentialsProvider(ProfileCredentialsProvider.create())
            .build();

    public static void writeItem(String testpartition, boolean isActive) {
        Map<String, AttributeValue> item = new HashMap<>();
        item.put("testpartition", AttributeValue.builder().s(testpartition).build());
        item.put("is_active", AttributeValue.builder().bool(isActive).build());
        PutItemRequest request = PutItemRequest.builder()
                .tableName(TABLE_NAME)
                .item(item)
                .build();
        client.putItem(request);
        System.out.println("Write successful");
    }

    public static void readItem(String testpartition) {
        Map<String, AttributeValue> key = new HashMap<>();
        key.put("testpartition", AttributeValue.builder().s(testpartition).build());
        GetItemRequest request = GetItemRequest.builder()
                .tableName(TABLE_NAME)
                .key(key)
                .build();
        GetItemResponse response = client.getItem(request);
        if (response.hasItem()) {
            System.out.println("Read result: " + response.item());
        } else {
            System.out.println("Item not found");
        }
    }

    public static void main(String[] args) {
        if (args.length < 2) {
            System.out.println("Usage: java DynamoDBReadWrite write <key> <true|false>");
            System.out.println("       java DynamoDBReadWrite read <key>");
            return;
        }
        String action = args[0];
        String key = args[1];
        if (action.equals("write") && args.length == 3) {
            boolean isActive = Boolean.parseBoolean(args[2]);
            writeItem(key, isActive);
        } else if (action.equals("read")) {
            readItem(key);
        } else {
            System.out.println("Invalid arguments");
        }
    }
}
