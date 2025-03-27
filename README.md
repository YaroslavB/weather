# Weather App

This project is a weather application built with PHP and Symfony. It uses Docker for containerization.

## Prerequisites

- Docker
- Docker Compose

## Installation

1. Clone the repository:

    ```sh
    git clone https://github.com/YOUR_USERNAME/weather-app.git
    cd weather-app
    ```

2. Copy the example environment file and update it with your configuration:

    ```sh
    cp .env.example .env
    ```

3. Update the `.env` file with your weather API key and other necessary configurations.

## Build

To build the Docker containers, run:

```sh
docker-compose build
```

##Run

To start the application, run:
```sh
docker-compose up -d
```
This will start the PHP and Nginx containers.

##Access the Application
Once the containers are up and running, you can access the application in your web browser at http://localhost.
