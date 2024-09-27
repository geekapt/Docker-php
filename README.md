# PHP, MySQL, Apache2 Demo Project

This is a simple demo project showcasing how to set up a PHP application with MySQL using Apache2, all within Docker containers.

## Prerequisites

- [Docker](https://www.docker.com/get-started) installed on your machine
- [Docker Compose](https://docs.docker.com/compose/install/) installed

## Project Structure

```
project/
├── Dockerfile
├── Makefile
├── LICENSE
├── README.md
├── docker-compose.yml
└── src/
    └── index.php
```

## Getting Started

Follow these steps to set up and run the project:

### 1. Clone the Repository

```bash
git clone https://github.com/geekapt/Docker-php.git
cd Docker-php
```

### 2. Create Your PHP Application

Inside the `src` directory, create an `index.php` file with the following content:

```php
<?php
$servername = "mysql";
$username = "user";
$password = "user_password";
$dbname = "sampledb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
```

### 3. Create the Dockerfile

Create a `Dockerfile` with the following content:

```Dockerfile
FROM php:8.0-apache
COPY src/ /var/www/html/
```

### 4. Create the Docker Compose File

Create a `docker-compose.yml` file with the following content:

```yaml
version: '3.8'

services:
  apache:
    build: .
    container_name: apache-web
    ports:
      - "8080:80"
    networks:
      - my-bridge-network
    depends_on:
      - mysql
    volumes:
      - ./src/:/var/www/html

  mysql:
    image: mysql:8.0
    container_name: mysql-db
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: sampledb
      MYSQL_USER: user
      MYSQL_PASSWORD: user_password
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - my-bridge-network

volumes:
  mysql_data:

networks:
  my-bridge-network:
    driver: bridge
```

### 5. Build and Run the Containers

In the project directory, run the following command to start the Docker containers:

```bash
docker-compose up -d
```

### 6. Access the Application

Open your web browser and go to `http://localhost:8080`. You should see the message "Connected successfully" if everything is set up correctly.

### 7. Stopping the Containers

To stop the running containers, use:

```bash
docker-compose down
```

## Troubleshooting

- Ensure that Docker and Docker Compose are installed correctly.
- Check the Docker container logs for any errors using:

```bash
docker-compose logs
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Acknowledgements

- [Docker](https://www.docker.com/)
- [PHP](https://www.php.net/)
- [MySQL](https://www.mysql.com/)
- [Apache](https://httpd.apache.org/)

