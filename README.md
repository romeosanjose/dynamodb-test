# PHP DynamoDB Application

This project is a simple PHP application that interacts with AWS DynamoDB to read and write data. It is designed to run on an EC2 instance with Nginx as the web server.

## Project Structure

```
php-dynamodb-app
├── src
│   ├── index.php        # Entry point of the application
│   ├── read.php         # Logic to read an item from DynamoDB
│   └── write.php        # Logic to write an item to DynamoDB
├── config
│   └── config.php       # Configuration settings for AWS DynamoDB
├── composer.json         # Composer dependencies
├── nginx
│   └── default.conf      # Nginx server configuration
└── README.md             # Project documentation
```

## Requirements

- PHP 7.4 or higher
- Composer
- AWS SDK for PHP

## Setup Instructions

1. **Clone the repository**:
   ```
   git clone <repository-url>
   cd php-dynamodb-app
   ```

2. **Install dependencies**:
   Run the following command to install the required PHP packages using Composer:
   ```
   composer install
   ```

3. **Configure AWS Credentials**:
   Update the `config/config.php` file with your AWS credentials and the DynamoDB table name.

4. **Configure Nginx**:
   Copy the `nginx/default.conf` file to your Nginx configuration directory and ensure it is set up to serve the PHP application.

5. **Start the Nginx server**:
   Make sure Nginx is running and configured to handle requests to your application.

## Usage

- To read an item from DynamoDB, send a GET request to `index.php` with the appropriate query parameters.
- To write an item to DynamoDB, send a POST request to `write.php` with the item data in the request body.

## License

This project is licensed under the MIT License.