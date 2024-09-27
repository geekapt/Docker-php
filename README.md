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
└── app/
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

Inside the `app` directory, create an `index.php` file with the following content:

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
# Use the official PHP image with Apache
FROM php:8.1-apache

# Install necessary dependencies for mysqli and other extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (optional if needed)
RUN a2enmod rewrite

# Copy the PHP application code to the Apache document root
COPY app/ /var/www/html/

# Copy custom php.ini if needed
COPY php.ini /usr/local/etc/php/php.ini

# Set file permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 for the web service
EXPOSE 80
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
      - ./app/:/var/www/html

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

### 5. Create the Makefile

Create a `Makefile` with the following content:

```makefile
run-dev:
	docker-compose up -d

down-dev:
	docker-compose down
```

#### About the Makefile

The `Makefile` is used to simplify the commands needed to run and manage your Docker containers. It allows you to define easy-to-remember commands for common tasks.

- `run-dev`: This command starts the Docker containers in detached mode using `docker-compose up -d`. You can run this by executing:

    ```bash
    make run-dev
    ```

- `down-dev`: This command stops and removes the Docker containers using `docker-compose down`. You can run this by executing:

    ```bash
    make down-dev
    ```

### 6. Build and Run the Containers

In the project directory, you can now use the `Makefile` to start the Docker containers:

```bash
make run-dev
```

### 7. Access the Application

Open your web browser and go to `http://localhost:8080`. You should see the message "Connected successfully" if everything is set up correctly.

### 8. Stopping the Containers

To stop the running containers using the `Makefile`, run:

```bash
make down-dev
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

