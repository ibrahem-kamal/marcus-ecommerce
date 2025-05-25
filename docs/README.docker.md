# Docker Setup for Marcus E-commerce

This document provides instructions for running the Marcus E-commerce application using Docker.

## Prerequisites

- Docker
- Docker Compose

## Files Created

1. `Dockerfile` - Defines the PHP environment with all necessary extensions and dependencies
2. `docker-compose.yml` - Orchestrates the services (PHP-FPM and Nginx)
3. `docker/nginx/conf.d/app.conf` - Nginx configuration for serving the Laravel application
4. `setup.sh` - Script to build and start the Docker environment
5. `stop.sh` - Script to stop and remove the Docker containers

## Getting Started

### Setup

To set up and start the application, run:

```bash
./setup.sh
```

This script will:
1. Build and start the Docker containers
2. Install PHP dependencies with Composer
3. Install JavaScript dependencies with npm
4. Generate the Laravel application key
5. Link the storage directory
6. Run database migrations and seeders
7. Build the frontend assets

Once complete, the application will be available at http://localhost:8000

### Stopping the Application

To stop and remove the Docker containers, run:

```bash
./stop.sh
```

## Manual Commands

If you prefer to run commands manually or need to run specific commands:

```bash
# Start the containers
docker-compose up -d

# Run composer commands
docker-compose exec app composer install

# Run artisan commands
docker-compose exec app php artisan migrate

# Run npm commands
docker-compose exec app npm install
docker-compose exec app npm run build

# Stop the containers
docker-compose down
```

## Dummy Data

- To add the fake data run the following command:

```bash
docker-compose exec app php artisan db:seed --class=DevSeeder
```
- This will populate the database with sample product for testing.