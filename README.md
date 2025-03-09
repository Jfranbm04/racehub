# RaceHub

RaceHub is a web application for managing running, cycling, and trail running races with participant registration and management.

## Prerequisites

- Docker and Docker Compose
- Git
- PHP 8.1+ (for local development without Docker)
- Composer (for local development without Docker)

## Getting Started

### Clone the Repository

```bash
git clone https://your-repository-url/racehubJWT.git
cd racehub
```

### Prepare the Environment

Before starting the Docker containers:

1. If you're on Windows, open Git Bash and run:

```bash
dos2unix ./backend/entrypoint.sh ./nginx/certs.sh
 ```

1. If you're on Linux, install dos2unix first and then run the same command:

```bash
sudo apt-get install dos2unix
dos2unix ./backend/entrypoint.sh ./nginx/certs.sh
 ```

1. Make sure there is no ./backend/composer.lock file the first time you start the project:

```bash
rm -f ./backend/composer.lock
 ```

### Running with Docker

1. Start the Docker containers:

```bash
docker-compose up --build
 ```

2. To run in detached mode (background):

```bash
docker-compose up -d
 ```

### Accessing the Application

- Web Interface: <http://localhost:81>
- PHPMyAdmin: <http://localhost:8080>

## Project Structure

- backend/ - Symfony backend with API Platform
- nginx/ - Nginx configuration for serving the application
- docker-compose.yml - Docker configuration

## Authentication

The application uses JWT (JSON Web Token) for authentication. Tokens are valid for 7 days.
